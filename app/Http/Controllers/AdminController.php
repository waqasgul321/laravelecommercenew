<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str; 
use Intervention\Image\Laravel\Facades\Image; // Ensure the correct facade is imported
use Illuminate\Support\Facades\File; 
use App\Models\category;
use App\Models\Product;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function brands()
    {
        $brands = Brand::orderBy('id', 'ASC')->paginate(5);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.add-brand');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048',
        ]);

        $brands = new Brand();
        $brands->name = $request->name;
        $brands->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;

            $this->GenerateBrandThumbnailImage($image, $file_name);
            $brands->image = $file_name;
        }

        $brands->save();
        return redirect()->route('admin.brand')->with('status', 'Record has been added successfully!');
    }

     public function brand_edit($id){
      
        $brand=Brand::find($id);
        return view('admin.edit-brand', compact('brand'));

     }

     public function update_brand(Request $request)
     {
         $request->validate([
             'name' => 'required',
             'slug' => 'required|unique:brands,slug,'.$request->id,
             'image' => 'mimes:png,jpg,jpeg|max:2048'
         ]);
         $brand = Brand::find($request->id);
         $brand->name = $request->name;
         $brand->slug = $request->slug;
         if($request->hasFile('image'))
         {            
             if (File::exists(public_path('uploads/brands').'/'.$brand->image)) {
                 File::delete(public_path('uploads/brands').'/'.$brand->image);
             }
             $image = $request->file('image');
             $file_extention = $request->file('image')->extension();
             $file_name = Carbon::now()->timestamp . '.' . $file_extention;
             $this->GenerateBrandThumbnailImage($image, $file_name);
             $brand->image = $file_name;
         }        
         $brand->save();        
         return redirect()->route('admin.brand')->with('status','Record has been updated successfully !');
     }



    public function GenerateBrandThumbnailImage($image, $imageName)
    {
        // Define the destination path
        $destinationPath = public_path('uploads/brands');

    
        // Process the image
        $img = Image::read($image->path()); // Ensure $image is a valid UploadedFile object
        $img->cover(124,124,"top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio(); // Prevent enlargement of smaller images
        });

        // Save the processed image
        $img->save($destinationPath . '/' . $imageName);

        return "Image generated and saved successfully!";
    }

    public function delete_brand($id)
    {
        $brand = Brand::find($id);
        if (File::exists(public_path('uploads/brands').'/'.$brand->image)) {
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brand')->with('status','Record has been deleted successfully !');
    }

    public function categories(){

        $categories= category::orderBy('id', 'DESC')->paginate(10); 
        return view('admin.categories',compact('categories'));
    


    }
    
public function add_category()
{
    return view("admin.category-add");
}
 
public function add_category_store(Request $request)
{        
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:categories,slug',
        'image' => 'mimes:png,jpg,jpeg|max:2048'
    ]);

    $category = new Category();
    $category->name = $request->name;
    $category->slug = Str::slug($request->name);
    $image = $request->file('image');
    $file_extention = $request->file('image')->extension();
    $file_name = Carbon::now()->timestamp . '.' . $file_extention;
    $this->GenerateCategoryThumbnailImage($image,$file_name);
    $category->image = $file_name;        
    $category->save();
    return redirect()->route('admin.categories')->with('status','Record has been added successfully !');
}

public function GenerateCategoryThumbnailImage($image, $imageName)
{
    // Define the destination path
    $destinationPath = public_path('uploads/categories');


    // Process the image
    $img = Image::read($image->path()); // Ensure $image is a valid UploadedFile object
    $img->cover(124,124,"top");
    $img->resize(124, 124, function ($constraint) {
        $constraint->aspectRatio(); // Prevent enlargement of smaller images
    });

    // Save the processed image
    $img->save($destinationPath . '/' . $imageName);

    return "Image generated and saved successfully!";
}

public function edit_category($id){

    $category= Category::find($id);

    return view('admin.category-edit', compact('category'));
}
public function update_category(Request $request ){

    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
      $category = Category::find($request->id);
      $category->name = $request->name;
      $category->slug = $request->slug;
        if($request->hasFile('image'))
        {            
            if (File::exists(public_path('uploads/categories').'/'.$category->image)) {
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateBrandThumbnailImage($image, $file_name);
          $category->image = $file_name;
        }        
      $category->save();        
        return redirect()->route('admin.categories')->with('status','category has been updated successfully !');
    }


}

public function delete_category($id)
{
    $category = Category::find($id);
    if (File::exists(public_path('uploads/categories').'/'.$category->image)) {
        File::delete(public_path('uploads/categories').'/'.$category->image);
    }
    $category->delete();
    return redirect()->route('admin.categories')->with('status','Record has been deleted successfully !');
}

public function products(){

    $products= Product::orderBy('created_at','DESC')->paginate(10);
    return view('admin.products',compact('products'));

}
public function add_product(){

    $categories= Category::select('id','name')->orderBy('name')->get();
    $brands= Brand::select('id','name')->orderBy('name')->get();
    return view('admin.add-products',compact('categories','brands'));
}
public function product_store(Request $request)
{
    $request->validate([
        'name'=>'required',
        'slug'=>'required|unique:products,slug',
        'category_id'=>'required',
        'brand_id'=>'required',            
        'short_description'=>'required',
        'description'=>'required',
        'regular_price'=>'required',
        'sale_price'=>'required',
        'SKU'=>'required',
        'stock_status'=>'required',
        'featured'=>'required',
        'quantity'=>'required',
        'image'=>'required|mimes:png,jpg,jpeg|max:2048'            
    ]);

    $product = new Product();
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->sale_price;
    $product->SKU = $request->SKU;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->quantity = $request->quantity;
    $product->category_id=$request->category_id;
    $product->brand_id=$request->brand_id;
    $current_timestamp = Carbon::now()->timestamp;

    if($request->hasFile('image'))
    {        
             
    
        $image = $request->file('image');
        $imageName = $current_timestamp.'.'.$image->extension();

        $this->GenerateproductThumbnailImage($image,$imageName);            
        $product->image = $imageName;
    }
    $gallery_arr=array();
    $gallery_image= "";
    $counter = 1;

    if ($request->hasFile('image')) {

        $allowedfileExtension=['jpg','png','jpeg'];
        $files = $request->file('images');
        foreach($files as $file)
        {                
            $gextension = $file->getClientOriginalExtension();                                
            $check=in_array($gextension,$allowedfileExtension);            
            if($check)
            {
                $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;   
                $this->GenerateproductThumbnailImage($file,$gfilename);                    
                array_push($gallery_arr,$gfilename);
                $counter = $counter + 1;
            }
        }
        $gallery_images = implode(',', $gallery_arr);
    }
    $product->images = $gallery_images;
    
    $product->save();
    return redirect()->route('admin.products')->with('status','product has been added successfully !');
}                             
              
        
    
public function GenerateproductThumbnailImage($image, $imageName){

    $destinationpaththumbnail=public_path('uploads/products/thumbnails');
    $destinationPath=public_path('uploads/products');

    $img = Image::read($image->path()); // Ensure $image is a valid UploadedFile object
   

    $img->cover(540,689,"top");
    $img->resize(540, 689, function ($constraint) {
        $constraint->aspectRatio(); 
        
    })->save($destinationPath . '/' . $imageName);

    
    
    $img->resize(124, 124, function ($constraint) {
        $constraint->aspectRatio(); 
        
    })->save($destinationpaththumbnail . '/' . $imageName);
 
}


public function edit_product($id)
{
    $product = Product::find($id);
    $categories = Category::select('id', 'name')->orderBy('name')->get();
    $brands = Brand::select('id', 'name')->orderBy('name')->get();

    return view('admin.product-edit', compact('product', 'categories', 'brands'));
}

public function update_product(Request $request)
{
    // Validate request data
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:products,slug,' . $request->id,
        'category_id' => 'required',
        'brand_id' => 'required',
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required|numeric',
        'sale_price' => 'required|numeric',
        'SKU' => 'required',
        'stock_status' => 'required',
        'featured' => 'required|boolean',
        'quantity' => 'required|integer',
        'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        'images.*' => 'nullable|mimes:png,jpg,jpeg|max:2048' // For gallery images
    ]);

    // Find product to update
    $product = Product::findOrFail($request->id);
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->sale_price;
    $product->SKU = $request->SKU;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->quantity = $request->quantity;
    $product->category_id = $request->category_id;
    $product->brand_id = $request->brand_id;

    $current_timestamp = Carbon::now()->timestamp;

    // Handle single image upload
    if ($request->hasFile('image')) {
        // Delete old image if it exists
        if ($product->image && File::exists(public_path('uploads/products/' . $product->image))) {
            File::delete(public_path('uploads/products/' . $product->image));
        }

        $image = $request->file('image');
        $imageName = $current_timestamp . '.' . $image->extension();

        $this->GenerateproductThumbnailImage($image, $imageName); // Thumbnail creation
        $product->image = $imageName;
    }

    // Handle gallery images
    $gallery_arr = [];
    $counter = 1;

    if ($request->hasFile('images')) {
        // Delete old gallery images
        if ($product->images) {
            foreach (explode(',', $product->images) as $oldFile) {
                if (File::exists(public_path('uploads/products/' . $oldFile))) {
                    File::delete(public_path('uploads/products/' . $oldFile));
                }
            }
        }

        // Save new gallery images
        $files = $request->file('images');
        foreach ($files as $file) {
            $gextension = $file->getClientOriginalExtension();
            $allowedfileExtension = ['jpg', 'png', 'jpeg'];
            if (in_array($gextension, $allowedfileExtension)) {
                $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
                $this->GenerateproductThumbnailImage($file, $gfilename); // Thumbnail creation
                $gallery_arr[] = $gfilename;
                $counter++;
            }
        }
    }
    $product->images = implode(',', $gallery_arr);

    // Save product
    $product->save();

    // Redirect back with success message
    return redirect()->route('admin.products')->with('status', 'Product has been updated successfully!');
}

public function delete_product($id)
{
    // Find the product or return a 404 error if not found
    $product = Product::findOrFail($id);

    // Delete the main product image if it exists
    if ($product->image && File::exists(public_path('uploads/products/' . $product->image))) {
        File::delete(public_path('uploads/products/' . $product->image));
    }

    // Delete gallery images if they exist
    if ($product->images) {
        foreach (explode(',', $product->images) as $galleryImage) {
            if (File::exists(public_path('uploads/products/' . $galleryImage))) {
                File::delete(public_path('uploads/products/' . $galleryImage));
            }
        }
    }

    // Delete the product record from the database
    $product->delete();

    // Redirect back with a success message
    return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully!');
}

              
    
}




