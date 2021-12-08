<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\JsonReturn;
use App\Models\User;
use App\Models\Location;
use App\Models\Inventory_brand;
use App\Models\Inventory_category;
use App\Models\Inventory_supplier;
use App\Models\Permission;
use App\Models\Country;
use App\Models\InventoryProducts;
use App\Models\InventoryOrders;
use App\Models\InventoryOrderItems;
use App\Models\InventoryOrderLogs;
use App\Models\Staff;
use App\Models\Taxes;
use App\Mail\PurchaseOrder;
use App\Mail\EmailBlast;
use DataTables;
use Session;
use Mail;
use PDF;
use Crypt;
use App\Exports\productexport;
use App\Exports\orderinfoexport;
use App\Exports\prstockexport;
use Excel;
use Illuminate\Validation\Rule;
use DB;
  
class InventoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$currentRoute = Route::currentRouteName();
		if($currentRoute != 'saveOrderPdf'){
			$this->middleware('auth');
		}
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
        $inventoryBrand = Inventory_brand::select('id','brand_name','updated_at')->where('is_deleted','0')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
        $categories = Inventory_category::select('id','category_name')->where('user_id', $AdminId)->where('is_deleted','0')->orderBy('id', 'desc')->get();
        $ProductSupplier = Inventory_supplier::select('id','supplier_name')->where('user_id', $AdminId)->where('is_deleted','0')->get();

        return view('inventory.index',compact('inventoryBrand','categories','ProductSupplier'));
    }

    public function productinfoexcel(){

        return Excel::download(new productexport(), 'Product List.xls');
    }

	public function productinfocsv(){

        return Excel::download(new productexport(), 'Product List.csv');
    }
    
	public function categories()
    {
        return view('inventory.inventory_categories');
    }

    public function addNewCategory(Request $request)
    {
        if ($request->ajax()) 
        {
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
            if($request->editCatId == "")
            {
				$rules = [
					// 'category_name' => 'required|unique:inventory_categories',
                    'category_name' => ['required',Rule::unique('inventory_categories')
                                ->where(function ($query) use ($request, $AdminId){
                                return $query->where('is_deleted', 0)
                                            ->where('user_id', $AdminId);
                                })],
				];
	
				$input = $request->only(
					'category_name'
				);
	
				$validator = Validator::make($input, $rules);
				if ($validator->fails()) 
				{
					return JsonReturn::error($validator->messages());
				}

                $addInventoryCategory = Inventory_category::create([
                    'user_id'                       => $AdminId,
                    'category_name'                 => $request->category_name,
                    'created_at'                    => date("Y-m-d H:i:s"),
                    'updated_at'                    => date("Y-m-d H:i:s")
                ]); 
                $data["status"] = true;
                $data["message"] = array("Category has been created succesfully.");
                return JsonReturn::success($data);  
            }
            else if($request->editCatId != "")
            {
				$rules = [
					// 'category_name' => 'required|unique:inventory_categories,category_name,'.$request->editCatId,
                    'category_name' => ['required',Rule::unique('inventory_categories')
                                ->where(function ($query) use ($request, $AdminId){
                                return $query->where('is_deleted', 0)
                                            ->where('user_id', $AdminId)
                                            ->where('id', '!=', $request->editCatId);
                                })],
				];
	
				$input = $request->only(
					'category_name'
				);
	
				$validator = Validator::make($input, $rules);
				if ($validator->fails()) 
				{
					return JsonReturn::error($validator->messages());
				}

                $Inventory_categoryOBJ = Inventory_category::find($request->editCatId);
                $Inventory_categoryOBJ->category_name = $request->category_name ? $request->category_name : "";
                $Inventory_categoryOBJ->updated_at = date("Y-m-d H:i:s");
                if($Inventory_categoryOBJ->save())
                {
                    $data["status"] = true;
                    $data["message"] = array("Category has been updated succesfully.");
                    return JsonReturn::success($data);
                }
                else
                {
                    $data["status"] = false;
                    $data["message"] = array("Category update operation is failed.");
                    return JsonReturn::success($data);
                }
            }
        }
        else
        {
            $data["status"] = false;
            $data["message"] = array("Sorry somethig went wrong.");
            $data["message_code"] = array("Out of ajax condition.");
            return JsonReturn::success($data);     
        }
    }

    public function getInventoryCategory(Request $request)
    {
        if ($request->ajax()) 
        {
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
            $inventoryCategory = Inventory_category::select('id','category_name')->where('is_deleted','0')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
            $data_arr = array();
            foreach($inventoryCategory as $val)
            {
                $product = InventoryProducts::select('category_id')->where('category_id','=',$val->id)->get();
                $tempData = array(
                    'id' => $val->id,
                    'products_assign' => $product->count(),
                    'category_name' => $val->category_name
                );
                array_push($data_arr, $tempData);
            }
         
            return Datatables::of($data_arr)
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    'data-products_assign' => function($row) {
                        return $row['products_assign'];
                    },
                    'data-name' => function($row) {
                        return $row['category_name'];
                    },
                    'class' => function($row) {
                        return "editCategoryModal";
                    },
                ])
                // ->setRowAttr(['data-toggle' => 'modal','data-target'=>'#editCategoryModal','class'=>'editCategoryModal'])
                ->make(true);
        }   
    }

    public function brands()
    {
        return view('inventory.inventory_brand');
    }

    public function deleteCategory(Request $request)
    {
        if ($request->ajax()) 
        {
            $category = Inventory_category::find($request->delCatId);
           
            if(!empty($category))
            {
                $deletedata = Inventory_category::where('id', $request->delCatId)->first();
                $deletedata->is_deleted = '1';
                $deletedata->save();
                
                if($deletedata){
                    $data["status"] = true;
                    $data["message"] = "Category has been deleted succesfully.";
                } else {
                    $data["status"] = false;
                    $data["message"] = "Something went wrong! Please try again.";
                }       
            } else {
                $data["status"] = false;
                $data["message"] = "Something went wrong! Please try again.";
            }   
            
            return JsonReturn::success($data);
        }
    }

    public function addNewBrand(Request $request)
    {
        if ($request->ajax()) 
        {
            $rules = [
                'brand_name' => 'required'
            ];

            $input = $request->only(
                'brand_name'
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return JsonReturn::error($validator->messages());
            }
			
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
            if($request->editBrandId == "")
            {
                $addInventoryBrand = Inventory_brand::create([
                    'user_id'                       => $AdminId,
                    'brand_name'                 	=> ($request->brand_name) ? $request->brand_name : '',
                    'created_at'                    => date("Y-m-d H:i:s"),
                    'updated_at'                    => date("Y-m-d H:i:s"),
                    
                ]); 
                $data["status"] = true;
                $data["message"] = array("Inventory brand has been created succesfully.");
                return JsonReturn::success($data);  
            }
            else if($request->editBrandId != "")
            {
                $Inventory_brandOBJ = Inventory_brand::find($request->editBrandId);
                $Inventory_brandOBJ->brand_name = $request->brand_name ? $request->brand_name : "";
                $Inventory_brandOBJ->updated_at = date("Y-m-d H:i:s");
                if($Inventory_brandOBJ->save())
                {
                    $data["status"] = true;
                    $data["message"] = array("Inventory brand has been updated succesfully.");
                    return JsonReturn::success($data);
                }
                else
                {
                    $data["status"] = false;
                    $data["message"] = array("Inventory brand update operation is failed.");
                    return JsonReturn::success($data);
                }
            }
        }
        else
        {
            $data["status"] = false;
            $data["message"] = array("Sorry somethig went wrong.");
            $data["message_code"] = array("Out of ajax condition.");
            return JsonReturn::success($data);     
        }
    }
    public function getInventoryBrands(Request $request)
    {
        if ($request->ajax()) 
        {
            $CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
            $inventoryBrand = Inventory_brand::select('id','brand_name','updated_at')->where('is_deleted','0')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
            
            $data_arr = array();
            foreach($inventoryBrand as $val)
            {
                $product = InventoryProducts::select('brand_id')->where('brand_id','=',$val->id)->get();
                $tempData = array(
                    'brand_name' => $val->brand_name,
                    'assigned' => $product->count(),
                    'updated_at' => date('d M Y, H:ma',strtotime($val->updated_at)),
                    'id' => $val->id,
                );
                array_push($data_arr, $tempData);
            }
            return Datatables::of($data_arr)
                
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    'data-name' => function($row) {
                        return htmlspecialchars_decode($row['brand_name']);
                    },
                    'data-assigned' => function($row) {
                        return htmlspecialchars_decode($row['assigned']);
                    },
                    'class' => function($row) {
                        return "editBrandModal";
                    },
                ])
                // ->setRowAttr(['data-toggle' => 'modal','data-target'=>'#editCategoryModal','class'=>'editCategoryModal'])
                ->make(true);
        }   
    }
    public function deleteBrand(Request $request)
    {
        if ($request->ajax()) 
        {
            $brand = Inventory_brand::find($request->deleteBrandId);
            
            if(!empty($brand))
            {
                $deletedata = Inventory_brand::where('id', $request->deleteBrandId)->first();
                $deletedata->is_deleted = '1';
                $deletedata->save();
                if($deletedata){
                    $data["status"] = true;
                    $data["message"] = "Brand has been deleted succesfully.";
                } else {
                    $data["status"] = false;
                    $data["message"] = "Something went wrong! Please try again.";
                }       
                
            } else {
                $data["status"] = false;
                $data["message"] = "Something went wrong! Please try again.";
            }   
            
            return JsonReturn::success($data);
        }
    }
	
    public function suppliers()
    {
        return view('inventory.inventory_suppliers');
    }
	
    public function addInventorySupplier($id = NULL)
    {
		$Country = Country::all();
		
        $inventory_supplier = array();
        if($id != "")
        {
            $inventory_supplier = Inventory_supplier::find($id);
        }
        return view('inventory.add_inventory_supplier',compact('inventory_supplier','Country'));
    }
    public function addNewSupplier(Request $request)
    {
        $rules = [
            'supplier_name' => 'required'
        ];

        $input = $request->only(
            'supplier_name'
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return JsonReturn::error($validator->messages());
        }
		
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
        $editSupplierID = $request->editSupplierID;
        if($editSupplierID == "")
        {
            $addSupplier = Inventory_supplier::create([
                'user_id'              => $AdminId,
                'supplier_name'        => ($request->supplier_name) ? $request->supplier_name : '',
                'supplier_description' => ($request->supplier_description) ? $request->supplier_description : '',
                'first_name'           => ($request->first_name) ? $request->first_name : '',
                'last_name'            => ($request->last_name) ? $request->last_name : '',
                'mobile_country_code'  => ($request->mobile_country_code) ? $request->mobile_country_code : 1,
                'mobile'               => ($request->mobile) ? $request->mobile : '',
                'email'                => ($request->email) ? $request->email : '',
                'tel_country_code'     => ($request->tel_country_code) ? $request->tel_country_code : 1,
                'telephone'            => ($request->telephone) ? $request->telephone : '',
                'website'              => ($request->website) ? $request->website : '',
                'address'              => ($request->address) ? $request->address : '',
                'suburb'               => ($request->suburb) ? $request->suburb : '',
                'city'                 => ($request->city) ? $request->city : '',
                'state'                => ($request->state) ? $request->state : '',
                'zip_code'             => ($request->zip_code) ? $request->zip_code : '',
                'country'              => ($request->country) ? $request->country : '',
				'is_postal_same'       => ($request->is_postal_same) ? 1 : 0,
				'postal_address'       => ($request->postal_address) ? $request->postal_address : '',
                'postal_suburb'        => ($request->postal_suburb) ? $request->postal_suburb : '',
                'postal_city'          => ($request->postal_city) ? $request->postal_city : '',
                'postal_state'         => ($request->postal_state) ? $request->postal_state : '',
                'postal_zip_code'      => ($request->postal_zip_code) ? $request->postal_zip_code : '',
                'postal_country'       => ($request->postal_country) ? $request->postal_country : '',
                'created_at'           => date("Y-m-d H:i:s"),
                'updated_at'           => date("Y-m-d H:i:s")
            ]); 

            $data["status"] = true;
            $data["message"] = array("Supplier has been created succesfully.");
            return JsonReturn::success($data);  
        }
        else
        {
            $Inventory_supplier = Inventory_supplier::find($editSupplierID);

            $Inventory_supplier->supplier_name = ($request->supplier_name) ? $request->supplier_name : '';
            $Inventory_supplier->supplier_description = ($request->supplier_description) ? $request->supplier_description : '';
            $Inventory_supplier->first_name = ($request->first_name) ? $request->first_name : '';
            $Inventory_supplier->last_name = ($request->last_name) ? $request->last_name : '';
            $Inventory_supplier->mobile_country_code = ($request->mobile_country_code) ? $request->mobile_country_code : 1;
            $Inventory_supplier->mobile = ($request->mobile) ? $request->mobile : '';
            $Inventory_supplier->email = ($request->email) ? $request->email : '';
            $Inventory_supplier->tel_country_code = ($request->tel_country_code) ? $request->tel_country_code : 1;
            $Inventory_supplier->telephone = ($request->telephone) ? $request->telephone : '';
            $Inventory_supplier->website = ($request->website) ? $request->website : '';
            $Inventory_supplier->address = ($request->address) ? $request->address : '';
            $Inventory_supplier->suburb = ($request->suburb) ? $request->suburb : '';
            $Inventory_supplier->city = ($request->city) ? $request->city : '';
            $Inventory_supplier->state = ($request->state) ? $request->state : '';
            $Inventory_supplier->zip_code = ($request->zip_code) ? $request->zip_code : '';
            $Inventory_supplier->country = ($request->country) ? $request->country : '';
			$Inventory_supplier->is_postal_same = ($request->is_postal_same) ? 1 : 0;
			$Inventory_supplier->postal_address = ($request->postal_address) ? $request->postal_address : '';
			$Inventory_supplier->postal_suburb = ($request->postal_suburb) ? $request->postal_suburb : '';
			$Inventory_supplier->postal_city = ($request->postal_city) ? $request->postal_city : '';
			$Inventory_supplier->postal_state = ($request->postal_state) ? $request->postal_state : '';
			$Inventory_supplier->postal_zip_code = ($request->postal_zip_code) ? $request->postal_zip_code : '';
			$Inventory_supplier->postal_country = ($request->postal_country) ? $request->postal_country : '';
            $Inventory_supplier->updated_at = date("Y-m-d H:i:s");
            if($Inventory_supplier->save())
            {
                $data["status"] = true;
                $data["message"] = array("Supplier has been updated succesfully.");
            }
            else
            {
                $data["status"] = true;
                $data["message"] = array("Something went wrong.");
            }
            return JsonReturn::success($data);     
        }
    }
	
    public function getInventorySupplier(Request $request)
    {
        if ($request->ajax()) 
        {
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
            $inventorySupplier = Inventory_supplier::select('id','supplier_name','mobile','email','updated_at')->where('is_deleted','0')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
            $data_arr = array();
            foreach($inventorySupplier as $val)
            {
                $product = InventoryProducts::select('supplier_id')->where('supplier_id','=',$val->id)->get();
                $tempData = array(
                    'supplier_name' => $val->supplier_name,
                    'mobile' => $val->mobile,
                    'email' => $val->email,
                    'assigned' => $product->count(),
                    'updated_at' => date('d M Y, H:ma',strtotime($val->updated_at)),
                    'id' => $val->id,
                );
                array_push($data_arr, $tempData);
            }
            return Datatables::of($data_arr)
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    'data-assigned' => function($row) {
                        return $row['assigned'];
                    },
                    'class' => function($row) {
                        return "editSupplierModal";
                    },
                ])
                // ->setRowAttr(['data-toggle' => 'modal','data-target'=>'#editCategoryModal','class'=>'editCategoryModal'])
                ->make(true);
        }   
    }
    public function deleteSupplier(Request $request)
    {
        if ($request->ajax()) 
        {
            $supplier = Inventory_supplier::find($request->deleteID);   
            if(!empty($supplier))
            {
                $deletedata = Inventory_supplier::where('id', $request->deleteID)->first();
                $deletedata->is_deleted = '1';
                $deletedata->save();
                
                if($deletedata){
                    $data["status"] = true;
                    $data["message"] = "Supplier has been deleted succesfully.";
                } else {
                    $data["status"] = false;
                    $data["message"] = "Sorry! Unable to delete supplier.";
                }       
                
            } else {
                $data["status"] = false;
                $data["message"] = "Sorry! Unable to find selected supplier.";
            } 
            return JsonReturn::success($data);
        }
    }
	
    public function addProduct()
    {
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
        $suppliers = Inventory_supplier::select('id','supplier_name','mobile','email','updated_at')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
        $categories = Inventory_category::select('id','category_name')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
        $brands = Inventory_brand::select('id','brand_name','updated_at')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
        $taxes = Taxes::where('user_id', $AdminId)->where('is_deleted', 0)->get();

		$phtml = '';
		foreach($taxes as $key => $value){
			$taxValue = explode(',',$value->tax_rates);
			$phtml .= '<option value="'. $value->id .'">'.$value->tax_name.'(';
			if(count($taxValue) > 1){
				$a = array();
				foreach($taxValue as $v){
					$tData = Taxes::select('tax_rates')->where('id',$v)->where('user_id', $AdminId)->where('is_deleted', 0)->first();
					if(!empty($tData)) {
						$tax_rate = $tData->tax_rates;
					}else{
						$tax_rate = NULL;
					}
					$a[] = $tax_rate;
				}
				$implod = implode(',', $a);
				$phtml .= $implod;
			}else{
				$phtml .= $value->tax_rates;
			}
			$phtml .= ') </option>';
		}


		return view('inventory.add_product',compact('suppliers','categories','brands', 'taxes', 'phtml'));
    }
	
	public function ajaxAddProduct(Request $request)
	{
		if ($request->ajax()) 
        {
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			if($request->editProductID == "") {
				$rules = [
					'product_name' => 'required|unique:inventory_products,deleted_at,NULL|max:255',
					'retail_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
					'barcode'      => 'required',
					'sku'          => 'required',
				];
			} else {
				$edit_id      = $request->editProductID;
				$product_name = $request->product_name;
				$barcode      = $request->barcode;
				$sku          = $request->sku;
				
				$rules = [
					'product_name' => 'required',
					'retail_price' => 'required|regex:/^\d+(\.\d{1,2})?$/'
				];
				
				$InventoryProductsDup = InventoryProducts::select('id')->where('product_name',$product_name)->where('user_id',$AdminId)->where('deleted_at',null)->where('id','!=',$edit_id)->orderBy('id', 'desc')->get();
				if(count($InventoryProductsDup) > 0){
					$data['status'] = false;
					$data['message'] = 'The title has already been taken.';
					return JsonReturn::success($data);
				}
				
				if($barcode != ''){
					$InventoryBarcodeDup = InventoryProducts::select('id')->where('barcode',$barcode)->where('user_id',$AdminId)->where('id','!=',$edit_id)->orderBy('id', 'desc')->get();
					if(count($InventoryBarcodeDup) > 0){
						$data['status'] = false;
						$data['message'] = 'The barcode has already been taken.';
						return JsonReturn::success($data);
					}	
				}
				
				if($sku != ''){
					$InventorySkuDup = InventoryProducts::select('id')->where('sku',$sku)->where('user_id',$AdminId)->where('id','!=',$edit_id)->orderBy('id', 'desc')->get();
					if(count($InventorySkuDup) > 0){
						$data['status'] = false;
						$data['message'] = 'The sku has already been taken.';
						return JsonReturn::success($data);
					}	
				}				
			}
			
            $input = $request->only(
                'product_name',
				'retail_price',
				'barcode',
				'sku'
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return JsonReturn::error($validator->messages());
            }

            $retailPrice = !empty($request->retail_price) ? $request->retail_price : 0.00;
            $specialRate = !empty($request->special_rate) ? $request->special_rate : 0.00;

            if($retailPrice < $specialRate) {
                $data['status'] = false;
                $data['message'] = 'Special rate cannot be greater than retail price.';
                return JsonReturn::success($data);
            }
			
            if($request->editProductID == "")
            {
				$barcode      = $request->barcode;
				$sku          = $request->sku;
				
				if($barcode != ''){
					$InventoryBarcodeDup = InventoryProducts::select('id')->where('barcode',$barcode)->where('user_id',$AdminId)->orderBy('id', 'desc')->get();
					if(count($InventoryBarcodeDup) > 0){
						$data['status'] = false;
						$data['message'] = 'The barcode has already been taken.';
						return JsonReturn::success($data);
					}	
				}
				
				if($sku != ''){
					$InventorySkuDup = InventoryProducts::select('id')->where('sku',$sku)->where('user_id',$AdminId)->orderBy('id', 'desc')->get();
					if(count($InventorySkuDup) > 0){
						$data['status'] = false;
						$data['message'] = 'The sku has already been taken.';
						return JsonReturn::success($data);
					}	
				}
				
                $createProduct = InventoryProducts::create([
                    'user_id'              => $AdminId,
                    'product_name'         => ($request->product_name) ? $request->product_name : '',
					'category_id'          => ($request->category_id) ? $request->category_id : 0,
					'brand_id'             => ($request->brand_id) ? $request->brand_id : 0,
					'enable_retail_sale'   => ($request->enable_retail_sale) ? 1 : 0,
					'retail_price'         => ($request->retail_price) ? $request->retail_price : 0,
					'special_rate'         => ($request->special_rate) ? $request->special_rate : 0,
					'tax_id'               => ($request->tax_id != '') ? $request->tax_id : null,
					'enable_commission'    => ($request->enable_commission) ? 1 : 0,
					'barcode'              => ($request->barcode) ? $request->barcode : '',
					'sku'                  => ($request->sku) ? $request->sku : '',
					'description'          => ($request->description) ? $request->description : '',
					'enable_stock_control' => ($request->enable_stock_control) ? 1 : 0,
					'supply_price'         => ($request->supply_price) ? $request->supply_price : 0,
					'initial_stock'        => ($request->initial_stock) ? $request->initial_stock : 0,
					'average_price'        => ($request->supply_price) ? $request->supply_price : 0,
					'supplier_id'          => ($request->supplier_id) ? $request->supplier_id : 0,
					'reorder_point'        => ($request->reorder_point) ? $request->reorder_point : 0,
					'reorder_qty'          => ($request->reorder_qty) ? $request->reorder_qty : 0,
                    'created_at'           => date("Y-m-d H:i:s"),
                    'updated_at'           => date("Y-m-d H:i:s")
                ]); 
				$lastId = InventoryProducts::orderBy("id", "DESC")->pluck("id")->first();
				if($request->initial_stock){
					$addStockLogs = InventoryOrderLogs::create([
						'item_id'              => $lastId,
						'received_date'        => date("Y-m-d H:i:s"),
						'received_by'          => Auth::id(),
						'supplier_id'          => ($request->supplier_id) ? $request->supplier_id : 0,
						'location_id'          => 0,
						'order_id'             => 0,
						'order_action'         => 'Initial Stock',
						'order_status'         => 0,
						'qty_adjusted'         => $request->initial_stock,
						'cost_price'           => $request->supply_price,
						'stock_on_hand'        => $request->initial_stock,
						'average_price'        => $request->supply_price,
						'enable_stock_control' => 0,
						'created_at'    => date("Y-m-d H:i:s")
					]);
				}
				
				Session::flash('message', 'Product has been added to inventory succesfully.');
				
                $data["status"] = true;
                $data["message"] = array("Product has been added to inventory succesfully.");
				$data["redirect"] = route('inventory');
                return JsonReturn::success($data);  
            }
            else if($request->editProductID != "")
            {
				$getPrevProductStatus = InventoryProducts::find($request->editProductID);
				
                $InventoryProductRow = InventoryProducts::find($request->editProductID);
				$InventoryProductRow->product_name         = ($request->product_name) ? $request->product_name : '';
				$InventoryProductRow->category_id          = ($request->category_id) ? $request->category_id : 0;
				$InventoryProductRow->brand_id             = ($request->brand_id) ? $request->brand_id : 0;
				$InventoryProductRow->enable_retail_sale   = ($request->enable_retail_sale) ? 1 : 0;
				$InventoryProductRow->retail_price         = ($request->retail_price) ? $request->retail_price : 0;
				$InventoryProductRow->special_rate         = ($request->special_rate) ? $request->special_rate : 0;
				$InventoryProductRow->tax_id               = ($request->tax_id != '') ? $request->tax_id : null;
				$InventoryProductRow->enable_commission    = ($request->enable_commission) ? 1 : 0;
				$InventoryProductRow->barcode              = ($request->barcode) ? $request->barcode : '';
				$InventoryProductRow->sku                  = ($request->sku) ? $request->sku : '';
				$InventoryProductRow->description          = ($request->description) ? $request->description : '';
				$InventoryProductRow->enable_stock_control = ($request->enable_stock_control) ? 1 : 0;
				$InventoryProductRow->supply_price         = ($request->supply_price) ? $request->supply_price : 0;
				$InventoryProductRow->initial_stock        = ($request->initial_stock) ? $request->initial_stock : 0;
				$InventoryProductRow->supplier_id          = ($request->supplier_id) ? $request->supplier_id : 0;
				$InventoryProductRow->reorder_point        = ($request->reorder_point) ? $request->reorder_point : 0;
				$InventoryProductRow->reorder_qty          = ($request->reorder_qty) ? $request->reorder_qty : 0;
				$InventoryProductRow->updated_at           = date("Y-m-d H:i:s");
				
                if($InventoryProductRow->save()) {
					
					$enable_stock_control = ($request->enable_stock_control) ? 1 : 0;
					
					if($getPrevProductStatus->enable_stock_control != $enable_stock_control){
						if($enable_stock_control == 0){
							$addStockLogs = InventoryOrderLogs::create([
								'item_id'              => $request->editProductID,
								'received_date'        => date("Y-m-d H:i:s"),
								'received_by'          => Auth::id(),
								'supplier_id'          => 0,
								'location_id'          => 0,
								'order_id'             => 0,
								'order_action'         => 'Stock tracking disabled',
								'order_status'         => 0,
								'qty_adjusted'         => 0,
								'cost_price'           => 0,
								'stock_on_hand'        => 0,
								'enable_stock_control' => 0,
								'created_at'    => date("Y-m-d H:i:s")
							]); 
						} else {
							$addStockLogs = InventoryOrderLogs::create([
								'item_id'       => $request->editProductID,
								'received_date' => date("Y-m-d H:i:s"),
								'received_by'   => Auth::id(),
								'supplier_id'   => 0,
								'location_id'   => 0,
								'order_id'      => 0,
								'order_action'  => 'Stock tracking enabled',
								'order_status'  => 0,
								'qty_adjusted'  => 0,
								'cost_price'    => 0,
								'stock_on_hand' => ($request->initial_stock) ? $request->initial_stock : 0,
								'enable_stock_control' => 1,
								'created_at'    => date("Y-m-d H:i:s")
							]); 
						}	
					}
					
					Session::flash('message', 'Product brand has been updated in inventory succesfully.');
                    $data["status"] = true;
                    $data["message"] = array("Product brand has been updated in inventory succesfully.");
					$data["redirect"] = route('inventory');
                    return JsonReturn::success($data);
                } else {
                    $data["status"] = false;
                    $data["message"] = array("Product update operation is failed.");
                    return JsonReturn::success($data);
                }
            }
        }
        else
        {
            $data["status"] = false;
            $data["message"] = array("Sorry somethig went wrong.");
            $data["message_code"] = array("Out of ajax condition.");
            return JsonReturn::success($data);     
        }
	}
	
	function getProductList(Request $request)
	{
		if ($request->ajax()) 
        {
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}

            $whereArray = [
                ['is_deleted', '=', 0],
                ['user_id', '=', $AdminId]
            ];
            
            if (!empty($request->get('brand'))) {
                $whereArray[] = ['brand_id', '=', $request->get('brand')];
            }
            if (!empty($request->get('cat'))) {
                $whereArray[] = ['category_id', '=', $request->get('cat')];
            }
            if (!empty($request->get('sup'))) {
                $whereArray[] = ['supplier_id', '=', $request->get('sup')];
            }
            if (!empty($request->get('instock'))) {
                $whereArray[] = ['initial_stock', '<=', 0];
            }
			
            $InventoryProducts = InventoryProducts::select('id','product_name','barcode','retail_price','special_rate','initial_stock','enable_stock_control','updated_at')->where($whereArray)->orderBy('id', 'desc')->get(); //'is_deleted','=','0')->where('user_id', $AdminId

            $data_arr = array();
			
            foreach($InventoryProducts as $val)
            {
                $tempData = array(
                    'id' => $val->id,
					'product_name'         => $val->product_name,
					'barcode'              => $val->barcode,
					'retail_price'         => $val->retail_price,
					'special_rate'         => $val->special_rate,
					'initial_stock'        => $val->initial_stock,
					'enable_stock_control' => $val->enable_stock_control,
					'updated_at'           => date('d M Y, H:ma',strtotime($val->updated_at)) 
                );
                array_push($data_arr, $tempData);
            }
			
            return Datatables::of($data_arr)
				->editColumn('retail_price', function ($row) {
					
					if($row['special_rate'] != "") { 
						$html2 = '<td>
									<s>CA $'.$row['retail_price'].'</s>
									<p class="font-weight-bolder text-danger">CA $'.$row['special_rate'].'</p>
								 </td>';
					} else {
						$html2 = '<td>
									<p class="font-weight-bolder">CA $'.$row['retail_price'].'</p>
								 </td>';	
					}	
					return $html2;
				})
				->editColumn('assigned', function ($row) {
                    $html = '<td class="text-right" style="text-align: right;">2</td>';
                    return $html;
                })
				->editColumn('initial_stock', function ($row) {
					if($row['enable_stock_control'] == 0){
						$html = '<td class="text-right" style="text-align: right;">Unlimited</td>';	
					} else {
						$html = '<td class="text-right" style="text-align: right;">'.$row['initial_stock'].'</td>';
					}
                    return $html;
                })
                /*->filter(function ($query) {
                    if (request()->has('brand')) {
                        $query->where([
                            ['brand_id', '=', $request->get('brand')],
                            ['category_id', '=', $request->get('cat')],
                            ['supplier_id', '=', $request->get('sup')],
                        ]);
                    }
                })*/
                ->rawColumns(['retail_price','assigned','initial_stock'])
				->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    'class' => function($row) {
                        return "editProduct";
                    },
                ])
				->make(true);
        } 
	}
	
    function filterProductList(Request $request)
	{
		if ($request->ajax()) 
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
            if($request->instock=='on'){
                $InventoryProducts = InventoryProducts::select('id','product_name','barcode','retail_price','special_rate','initial_stock','updated_at')
                                ->where('is_deleted','=','0')
                                ->where('user_id', $AdminId)
                                ->where([
                                    ['brand_id', '=', $request->brand],
                                    ['category_id', '=', $request->cate],
                                    ['supplier_id', '=', $request->supp],
                                    ['initial_stock', '>', 0],
                                ])
                                ->orderBy('id', 'desc')->get();
            } else {
                $InventoryProducts = InventoryProducts::select('id','product_name','barcode','retail_price','special_rate','initial_stock','updated_at')
                                ->where('is_deleted','=','0')
                                ->where('user_id', $AdminId)
                                ->where([
                                    ['brand_id', '=', $request->brand],
                                    ['category_id', '=', $request->cate],
                                    ['supplier_id', '=', $request->supp],
                                ])
                                ->orderBy('id', 'desc')->get();
            }
            

            $data_arr = array();
			
            foreach($InventoryProducts as $val)
            {
                $tempData = array(
                    'id' => $val->id,
					'product_name'  => $val->product_name,
					'barcode'       => $val->barcode,
					'retail_price'  => $val->retail_price,
					'special_rate'  => $val->special_rate,
					'initial_stock' => $val->initial_stock,
					'updated_at'    => date('d M Y, H:ma',strtotime($val->updated_at)) 
                );
                array_push($data_arr, $tempData);
            }
			
            $data["status"] = true;
			$data["Data"] = $data_arr;
			return JsonReturn::success($data); 
        } 
	}

	function viewProductItem($id = NULL)
	{
		// $Country = Country::all();
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}

        $InventoryProducts  = array();
		$ProductSupplier    = array();
		$InventoryOrderLogs = array();
		$AllLocationData    = array();
		
		$TotalStockCost = 0;
		$TotalCostPrice = 0;
		$AvgStockCost   = 0;
		$TotalOrders    = 0;
		$TotalQtyAdjusted    = 0;
		
        if($id != "") {
			$InventoryProducts = InventoryProducts::select('inventory_products.*','inventory_categories.category_name','inventory_brands.brand_name')->leftJoin('inventory_categories', 'inventory_categories.id', '=', 'inventory_products.category_id')->leftJoin('inventory_brands', 'inventory_brands.id', '=', 'inventory_products.brand_id')->where('inventory_products.user_id', $AdminId)->where('inventory_products.id', $id)->orderBy('inventory_products.id', 'desc')->first();
			
			if($InventoryProducts->supplier_id != '' && $InventoryProducts->supplier_id != 0) {
				$ProductSupplier = Inventory_supplier::select('id','supplier_name')->where('user_id', $AdminId)->where('id',$InventoryProducts->supplier_id)->get();	
			}
			
			// $InventoryOrderLogs = InventoryOrderLogs::select('inventory_order_logs.*')->where('item_id',$id)->get()->toArray();	

			$InventoryOrderLogs = InventoryOrderLogs::select(DB::raw('SUM(stock_on_hand * average_price) as TotalStockCost'), DB::raw('SUM(cost_price) as TotalCostPrice'), DB::raw('SUM(qty_adjusted) as TotalQtyAdjusted') )
			->where('item_id',$id)
			->first();	

			$TotalStockCostData = InventoryOrderLogs::where('item_id', $id)->orderBy("id", "DESC")->select("stock_on_hand", "average_price")->first();
			// echo $TotalStockCostData;die;
			
			if(!empty($InventoryOrderLogs))
			{
				// $TotalCostPrice = $InventoryOrderLogs->TotalCostPrice;
				// $TotalStockCost = $InventoryOrderLogs->TotalStockCost;
				$TotalStockCost = $TotalStockCostData->stock_on_hand * $TotalStockCostData->average_price;
				$TotalQtyAdjusted = $InventoryOrderLogs->TotalQtyAdjusted;

				if($TotalStockCost != 0 && $TotalQtyAdjusted != 0){
					$AvgStockCost = number_format($TotalStockCost / $TotalQtyAdjusted, 2);
				}
			}
			
			/* if(!empty($InventoryOrderLogs))
			{
				foreach($InventoryOrderLogs as $InventoryOrderLogData)
				{
					$TotalOrders++;
					$TotalProductPrice = ($InventoryOrderLogData['qty_adjusted'] * $InventoryOrderLogData['cost_price']); 
					$qtyAdjusted += $InventoryOrderLogData['qty_adjusted'];
					$TotalStockCost = $TotalStockCost + $TotalProductPrice;
					$TotalCostPrice = $TotalCostPrice + $InventoryOrderLogData['cost_price'];
					echo 'qty adjusted::'.$InventoryOrderLogData['qty_adjusted']."<br>";
					echo 'cost price::'.$InventoryOrderLogData['cost_price']."<br>";
					echo 'total product price::'.$TotalProductPrice."<br><br>";
				}
				
				if($TotalOrders != 0 && $TotalCostPrice != 0 && $qtyAdjusted != 0){
					$AvgStockCost = ceil(round($TotalStockCost / $qtyAdjusted));
				}
			} */
			// echo 'total cost price::'.$TotalCostPrice.' --------- total stock cost::'.$TotalStockCost;die;
			
			$LocationArray = Location::select('id','location_name','location_address')->where('user_id', $AdminId)->get();
			if(!empty($LocationArray))
			{
				foreach($LocationArray as $LocationData)
				{
					$tempLocationData['id'] = $LocationData->id;
					$tempLocationData['location_name'] = $LocationData->location_name;
					
					$getLocationBasedStock = InventoryOrderLogs::where('location_id', $LocationData->id)->where('item_id', $id)->sum('inventory_order_logs.qty_adjusted'); //->where('order_status', 0)
					
					$tempLocationData['in_stock'] = ($getLocationBasedStock) ? $getLocationBasedStock : 0;
					array_push($AllLocationData,$tempLocationData);
				}
			}
        }
		
		return view('inventory.viewProduct',compact('InventoryProducts','ProductSupplier','TotalStockCost','AvgStockCost','AllLocationData'));
	}
	
	function increaseProductStock(Request $request)
	{
		if ($request->ajax()) 
        {			
			$rules = [
				'instock_item_id'      => 'required',
				'instock_location_id'  => 'required',
				'increase_stock_qty'   => 'required',
				'increase_stock_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
				'order_action'         => 'required'
			];
			
            $input = $request->only(
                'instock_item_id',
				'instock_location_id',
				'increase_stock_qty',
				'increase_stock_price',
				'order_action'
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return JsonReturn::error($validator->messages());
            }
			
			$InventoryProducts = InventoryProducts::find($request->instock_item_id);
			
			$save_stock_item_price = ($request->save_stock_item_price) ? 1 : 0;
			
			$PrevStockQty = 0;
			if(!empty($InventoryProducts)){
				$PrevStockQty = $InventoryProducts->initial_stock; 
			}
			$TotalStock = $PrevStockQty + $request->increase_stock_qty;
			
			$InventoryOrderLogs = InventoryOrderLogs::select(DB::raw('SUM(qty_adjusted * average_price) as TotalStockCost'), DB::raw('SUM(cost_price) as TotalCostPrice'), DB::raw('SUM(qty_adjusted) as TotalQtyAdjusted') )
			->where('item_id',$request->instock_item_id)
			->first();	

			$TotalStockCostData = InventoryOrderLogs::where('item_id', $request->instock_item_id)->orderBy("id", "DESC")->select("stock_on_hand", "average_price")->first();

			// dd($InventoryOrderLogs);
			
			if(!empty($InventoryOrderLogs))
			{
				$newStock1 = $request->increase_stock_qty * $request->increase_stock_price;
				// $TotalCostPrice = $InventoryOrderLogs->TotalCostPrice;
				// $TotalStockCost = $InventoryOrderLogs->TotalStockCost;
				$TotalStockCost = $TotalStockCostData->stock_on_hand * $TotalStockCostData->average_price;
				$TotalStockCost += $newStock1;
				$TotalQtyAdjusted = $InventoryOrderLogs->TotalQtyAdjusted;
				$TotalQtyAdjusted += $request->increase_stock_qty;

				if($TotalStockCost != 0 && $TotalQtyAdjusted != 0){
					$AvgStockCost = number_format($TotalStockCost / $TotalQtyAdjusted, 5);
				}
			}
			// dd($TotalStockCost);
			
			$addStockLogs = InventoryOrderLogs::create([
				'item_id'              => $request->instock_item_id,
				'received_date'        => date("Y-m-d H:i:s"),
				'received_by'          => Auth::id(),
				'supplier_id'          => 0,
				'location_id'          => $request->instock_location_id,
				'order_id'             => 0,
				'order_action'         => ($request->order_action) ? $request->order_action : '',
				'order_status'         => 0,
				'qty_adjusted'         => $request->increase_stock_qty,
				'cost_price'           => $request->increase_stock_price,
				'stock_on_hand'        => $TotalStock,
				'average_price'        => $AvgStockCost,
				'enable_stock_control' => $InventoryProducts->enable_stock_control,
				'created_at'           => date("Y-m-d H:i:s")
			]); 
			
			if($addStockLogs){
				$InventoryProducts->initial_stock = $TotalStock;
				if($save_stock_item_price == 1){
					$InventoryProducts->supply_price = $request->increase_stock_price;
				}
				$InventoryProducts->updated_at    = date("Y-m-d H:i:s");
				
				$InventoryProducts->save();
				
				Session::flash('message', 'Stock has been added to the product.');
				$data["status"] = true;
				$data["message"] = "Stock has been added to the product.";	
			} else {
				$data["status"] = false;
				$data["message"] = array("Sorry somethig went wrong.");
			}
			
			return JsonReturn::success($data);  
        }
        else
        {
            $data["status"] = false;
            $data["message"] = array("Sorry somethig went wrong.");
            $data["message_code"] = array("Out of ajax condition.");
            return JsonReturn::success($data);     
        }
	}
	
	function decreaseProductStock(Request $request)
	{
		if ($request->ajax()) 
        {			
			$rules = [
				'outstock_item_id'      => 'required',
				'outstock_location_id'  => 'required',
				'decrease_stock_qty'   => 'required',
				'out_order_action'         => 'required'
			];
			
            $input = $request->only(
                'outstock_item_id',
				'outstock_location_id',
				'decrease_stock_qty',
				'out_order_action'
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return JsonReturn::error($validator->messages());
            }
			
			$InventoryProducts = InventoryProducts::find($request->outstock_item_id);
			
			$save_stock_item_price = ($request->save_stock_item_price) ? 1 : 0;
			
			$PrevStockQty = 0;
			if(!empty($InventoryProducts)){
				$PrevStockQty = $InventoryProducts->initial_stock; 
			}
			$TotalStock = $PrevStockQty - $request->decrease_stock_qty;

			$InventoryOrderLogs = InventoryOrderLogs::select('average_price')
			->where('item_id',$request->outstock_item_id)
			->orderBy("id", "DESC")
			->first();
			
			$addStockLogs = InventoryOrderLogs::create([
				'item_id'              => $request->outstock_item_id,
				'received_date'        => date("Y-m-d H:i:s"),
				'received_by'          => Auth::id(),
				'supplier_id'          => 0,
				'location_id'          => $request->outstock_location_id,
				'order_id'             => 0,
				'order_action'         => ($request->out_order_action) ? $request->out_order_action : '',
				'order_status'         => 0,
				'qty_adjusted'         => (-1 * abs($request->decrease_stock_qty)),
				'cost_price'           => $InventoryProducts->supply_price,
				'stock_on_hand'        => $TotalStock,
				'average_price'        => $InventoryOrderLogs->average_price,
				'enable_stock_control' => $InventoryProducts->enable_stock_control,
				'created_at'           => date("Y-m-d H:i:s")
			]); 
			
			if($addStockLogs){
				$InventoryProducts->initial_stock = $TotalStock;
				$InventoryProducts->updated_at    = date("Y-m-d H:i:s");
				
				$InventoryProducts->save();
				
				Session::flash('message', 'Qty has been remove from the product stock.');
				$data["status"] = true;
				$data["message"] = "Qty has been remove from the product stock.";	
			} else {
				$data["status"] = false;
				$data["message"] = array("Sorry somethig went wrong.");
			}
			
			return JsonReturn::success($data);  
        }
        else
        {
            $data["status"] = false;
            $data["message"] = array("Sorry somethig went wrong.");
            $data["message_code"] = array("Out of ajax condition.");
            return JsonReturn::success($data);     
        }
	}
	
	function getInventoryOrderLogs(Request $request)
	{
		if ($request->ajax()) 
        {
			$item_id = ($request->item_id) ? $request->item_id : 0;
			
            $InventoryOrderLogs = InventoryOrderLogs::select('inventory_order_logs.*','users.first_name','users.last_name')->join('users','users.id','=','inventory_order_logs.received_by')->where('item_id',$item_id)->orderBy('inventory_order_logs.id', 'desc')->get();	
			
            $data_arr = array();
			
            foreach($InventoryOrderLogs as $val)
            {
				if($val->location_id != 0){
					$getLocation = Location::getLocationByID($val->location_id);	
					$LocationName = $getLocation->location_name;
				} else {
					$getLocation = Location::getLocationByID($val->location_id);
					$LocationName = '';
				}
				
                $tempData = array(
                    'created_at' => ($val->created_at) ? date("d M Y, H:ia",strtotime($val->created_at)) : '',
					'received_by'  => $val->first_name.' '.$val->last_name,
					'location_name'  => $LocationName,
					'order_id'       => $val->order_id,
					'invoice_id'     => $val->invoice_id,
					'order_type'     => $val->order_type,
					'order_action'   => $val->order_action,
					'qty_adjusted'   => $val->qty_adjusted,
					'cost_price'     => 'CA $'.$val->cost_price,
					'stock_on_hand'  => ($val->enable_stock_control == 1) ? $val->stock_on_hand : 'Unlimited',
					'is_void_invoice'=> $val->is_void_invoice
                );
                array_push($data_arr, $tempData);
            }
			
            return Datatables::of($data_arr)
				->editColumn('order_id', function ($row) {
					if($row['order_type'] == 0){
						if($row['order_id'] == 0){
							$order_html = '<td>'.$row['order_action'].'</td>';
						} else {
							$order_html = '<td><a href="'.route('viewOrder',['id' => $row['order_id']]).'">Order P'.$row['order_id'].'</a></td>';	
						}	
					} else {
						if($row['is_void_invoice'] == 1){
							$order_html = '<td>'.$row['order_action'].'</td>';
						} else if($row['invoice_id'] == 0){
							$order_html = '<td>'.$row['order_action'].'</td>';
						} else {
							$order_html = '<td><a href="'.route('viewInvoice',['id' => $row['invoice_id']]).'">Invoice '.$row['invoice_id'].'</a></td>';	
						}	
					}
					return $order_html;
				})
				->rawColumns(['order_id'])
				->make(true);
        } 
	}
	
    public function StockHistoryexcel($id){

        return Excel::download(new prstockexport($id), 'Stock History.xls');
    }

	public function StockHistorycsv($id){

        return Excel::download(new prstockexport($id), 'Stock History.csv');
    }

	function editProductItem($id = NULL)
	{
		$Country = Country::all();
		
        $InventoryProducts = array();
        if($id != "") {
            $InventoryProducts = InventoryProducts::find($id);
        }
		
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
        
		$suppliers = Inventory_supplier::select('id','supplier_name','mobile','email','updated_at')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
        $categories = Inventory_category::select('id','category_name')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
        $brands = Inventory_brand::select('id','brand_name','updated_at')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
        $taxes = Taxes::where('user_id', $AdminId)->where('is_deleted', 0)->get();
		$phtml = '';
		foreach($taxes as $key => $value){
			$selected = '';
			if(!empty($InventoryProducts)){
				if($value->id == $InventoryProducts->tax_id){
					$selected = 'selected';
				}
			}
			$taxValue = explode(',',$value->tax_rates);
			$phtml .= '<option value="'. $value->id .'" '. $selected .' >'.$value->tax_name.'(';
			if(count($taxValue) > 1){
				$a = array();
				foreach($taxValue as $v){
					$tData = Taxes::select('tax_rates')->where('id',$v)->where('user_id', $AdminId)->where('is_deleted', 0)->first();
					if(!empty($tData)) {
						$tax_rate = $tData->tax_rates;
					}else{
						$tax_rate = NULL;
					}
					$a[] = $tax_rate;
				}
				$implod = implode(',', $a);
				$phtml .= $implod;
			}else{
				$phtml .= $value->tax_rates;
			}
			$phtml .= ') </option>';
		}		
		return view('inventory.add_product',compact('suppliers','categories','brands','InventoryProducts','taxes','phtml'));
	}
	
	public function deleteProductItem(Request $request)
    {
        if ($request->ajax()) 
        {
            $InventoryProducts = InventoryProducts::find($request->deleteID);   
            if(!empty($InventoryProducts))
            {
				$InventoryProducts->is_deleted = 1;
				$InventoryProducts->save();
				
                $deletedata = InventoryProducts::where('id', $request->deleteID)->first();
                $deletedata->is_deleted = '1';
                $deletedata->save();
                if($deletedata){
                    $data["status"] = true;
                    $data["message"] = array("Product has been deleted succesfully.");
					$data["redirect"] = route('inventory');
                } else {
                    $data["status"] = false;
                    $data["message"] = "Sorry! Unable to delete product.";
                }       
            } else {
                $data["status"] = false;
                $data["message"] = "Sorry! Unable to find selected product.";
            } 
            return JsonReturn::success($data);
        }
    }
	
	public function ordersList()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
        $ProductSupplier = Inventory_supplier::select('id','supplier_name')->where('user_id', $AdminId)->where('is_deleted','0')->get();

		return view('inventory.orders',compact('ProductSupplier'));
	}
	
    public function ordersexcel(){

        return Excel::download(new orderinfoexport(), 'Order List.xls');
    }

	public function orderscsv(){

        return Excel::download(new orderinfoexport(), 'Order List.csv');
    }

	public function getOrders(Request $request)
	{
		if ($request->ajax()) 
        {
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
            $InventoryOrders = InventoryOrders::select('id','created_at','supplier_id','order_status','order_total')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
			
            $data_arr = array();
			
            foreach($InventoryOrders as $val)
            {
                $tempData = array(
                    'id'           => $val->id,
					'created_at'   => date("d M Y",strtotime($val->created_at)),
					'supplier_name'  => $val->supplier_id,
					'order_status' => $val->order_status,
					'order_total'  => 'CA $'.$val->order_total
                );
                array_push($data_arr, $tempData);
            }
			
            return Datatables::of($data_arr)
				->editColumn('id', function ($row) {
					$id = '<td><a href="'.route('viewOrder',['id' => $row['id']]).'" class="text-blue cursor-pointer">P'.$row['id'].'</a></td>';
					return $id;
				})
				->editColumn('supplier_name', function ($row) {
					
					$OrderSupplier = Inventory_supplier::getSupplierbyID($row['supplier_name']);
					
                    $supplier_name = '<td>'.$OrderSupplier->supplier_name.'</td>';
                    return $supplier_name;
                })
				->editColumn('order_status', function ($row) {
					$order_status = '';
					if($row['order_status'] == 1){
						$order_status = '<td><span class="badge badge-pill badge-warning">Ordered</span></td>';
					} else if($row['order_status'] == 2){
						$order_status = '<td><span class="badge badge-pill badge-success">Received</span></td>';
					} else if($row['order_status'] == 3){
						$order_status = '<td><span class="badge badge-pill badge-danger">Cancelled</span></td>';
					} else {
						$order_status = '<td><span class="badge badge-pill badge-danger">N/A</td>';
					}
                    return $order_status;
                })
                ->rawColumns(['id','supplier_name','order_status'])
				->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    'class' => function($row) {
                        return "editInventoryOrder";
                    },
                ])
				->make(true);
        }
	}

    public function filterorder(Request $request){

        if ($request->ajax()) 
        {
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
            $InventoryOrders = InventoryOrders::select('id','created_at','supplier_id','order_status','order_total')
                                ->where('user_id', $AdminId)
                                ->where([
                                    ['order_status', '=', $request->status],
                                    ['supplier_id', '=', $request->supp],
                                ])
                                ->orderBy('id', 'desc')->get();
			
            $data_arr = array();

			foreach($InventoryOrders as $val)
            {
				$OrderSupplier = Inventory_supplier::getSupplierbyID($val->supplier_id);
					
				$tempData = array(
                    'id'           => $val->id,
					'created_at'   => date("d M Y",strtotime($val->created_at)),
					'supplier_name'  => $OrderSupplier->supplier_name,
					'order_status' => $val->order_status,
					'order_total'  => 'CA $'.$val->order_total
                );
                array_push($data_arr, $tempData);
            }
			
            $data["status"] = true;
			$data["Data"] = $data_arr;
			return JsonReturn::success($data); 
        } 
    }
	
	public function createOrder()
	{	
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		// Get all locations
		$Locations = Location::select('id','location_name','location_address')->where('user_id', $AdminId)->orderBy('id', 'asc')->get();
		$TotalLocations = $Locations->count();
		
		$LocationArray = $Locations->toArray();
		
		// Get all suppliers
		$InventorySupplier = Inventory_supplier::select('id','supplier_name','address','suburb','city','state','zip_code','country')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
		
		// Get all category
		$InventoryCategory = Inventory_category::select('id','category_name')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
		
		return view('inventory.addOrder',compact('InventorySupplier','LocationArray','InventoryCategory','TotalLocations'));
	}
	
	public function getProductCategories(Request $request){
		if ($request->ajax())
		{  
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$searchName = ($request->searchKeyWord) ? $request->searchKeyWord : '';
			
			if($searchName != ''){
				$categories = Inventory_category::select('id','category_name')->where('category_name','LIKE','%'.$searchName.'%')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
			} else {
				$categories = Inventory_category::select('id','category_name')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
			}
			
			$html = '';
			if(!empty($categories)){
				foreach($categories as $categoriedata){
					$html .= '<li type="button" onclick="nextPrevModal(1)" data-category_id="'.$categoriedata->id.'" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action selectProductCategory"> '.$categoriedata->category_name.' <i class="fa fa-chevron-right"></i> </li>';
				}
				$html .= '<li type="button" onclick="nextPrevModal(1)" data-category_id="0" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action selectProductCategory"> No Category <i class="fa fa-chevron-right"></i> </li>';
			} else {
				$html .= '<li type="button" onclick="nextPrevModal(1)" data-category_id="0" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action selectProductCategory"> No Category <i class="fa fa-chevron-right"></i> </li>';
			}
			
			$data['html'] = $html;
			
            return JsonReturn::success($data);
        }
	}
	
	public function getProductsList(Request $request)
	{
		if ($request->ajax())
		{  
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$searchName = ($request->searchKeyWord) ? $request->searchKeyWord : '';
			$category_id = ($request->category_id) ? $request->category_id : '';
			$supplier_id = ($request->supplier_id) ? $request->supplier_id : '';
			
			if($searchName != ''){
				$InventoryProducts = InventoryProducts::select('id','product_name','initial_stock','special_rate')->where('product_name','LIKE','%'.$searchName.'%')->where(['user_id' => $AdminId,'category_id'=>$category_id])->orderBy('id', 'desc')->get()->toArray();
			} else {
				$InventoryProducts = InventoryProducts::select('id','product_name','initial_stock','special_rate')->where(['user_id' => $AdminId,'category_id'=>$category_id])->orderBy('id', 'desc')->get()->toArray();
			}
            
			$html = '';
			if(!empty($InventoryProducts)){
				foreach($InventoryProducts as $ProductsData){
					
					$html .= '
					<li type="button" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action createOrder" data-pro-id="'.$ProductsData["id"].'">
						<span>
							<p class="m-0">'.$ProductsData["product_name"].'</p>
							<p class="m-0">'.$ProductsData["initial_stock"].' in stock</p>
						</span>
						<p class="font-weight-bolder">CA $'.$ProductsData["special_rate"].'</p>
					</li>';
				}
			} else {
				$html .= '
				<li class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
					<p class="font-weight-bolder">Products not found.</p>
				</li>';
			}
			
			$data['html'] = $html;
			
            return JsonReturn::success($data);
        }
	}

    public function addToCartItem(Request $request)
    {
        if ($request->ajax())
        {  
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
            $category_id = ($request->category_id) ? $request->category_id : '';
            $supplier_id = ($request->supplier_id) ? $request->supplier_id : '';
            $product_id = ($request->product_id) ? $request->product_id : '';

            $InventoryProducts = InventoryProducts::select('*')->where(['user_id' => $AdminId,'id'=>$product_id])->orderBy('id', 'desc')->get()->toArray();

            $html = '';
            if(!empty($InventoryProducts))
            {
				$cart_item_category_id = ($InventoryProducts[0]["category_id"]) ? $InventoryProducts[0]["category_id"] : 0;
				$cart_item_supplier_id = ($InventoryProducts[0]["supplier_id"]) ? $InventoryProducts[0]["supplier_id"] : 0;
				
                $uid = $this->unique_code(10);

                $reorderQty = is_numeric($InventoryProducts[0]["reorder_qty"]) ? $InventoryProducts[0]["reorder_qty"] : 0.00;
                $specialRate = is_numeric($InventoryProducts[0]["special_rate"]) ? $InventoryProducts[0]["special_rate"] : 0.00;

                $totalPrice = $reorderQty * $specialRate;
                $html .= '
				<tr class="'.$uid.'">
                    <td class="p-4">
                        '.$InventoryProducts[0]["product_name"].'
                    </td>
                    <td>
						<input type="hidden" name="cart_item_category_id[]" value="'.$cart_item_category_id.'">
                        <input type="hidden" name="cart_item_ids[]" value="'.$InventoryProducts[0]["id"].'">
                        <input type="text" name="cart_item_qty[]" id="item_qty_'.$uid.'" class="form-control rounded-0 cart_item_qty" placeholder="0" data-pprice="'.$InventoryProducts[0]["special_rate"].'" data-uid="'.$uid.'" onkeypress="return validQty(event,this.value);" required="required" value="'.$InventoryProducts[0]["reorder_qty"].'" />
                    </td>
                    <td>
                        <input type="text" name="cart_item_price[]" id="pprice_'.$uid.'" value="'.$InventoryProducts[0]["special_rate"].'" class="form-control rounded-0 all_product_prices" data-uid="'.$uid.'" placeholder="0" onkeypress="return validPrice(event,this.value);" required/>
                    </td>
                    <td class="p-4">
                        CA $<div id="total_pprice_'.$uid.'">'.$totalPrice.'</div>
                        <input type="hidden" class="all_product_total" id="all_product_total'.$uid.'" value="'.$totalPrice.'">
                    </td>
                    <td>
                        <a href="javascript:;" onclick="removeCartProduct(\''.$uid.'\');"><i class="fa fa-times icon-lg"></i></a>
                    </td>
                </tr>';
            } 
            else 
            {
                $html .= '<p>Sorry! Something went wrong</p>';
            }
            $data['html'] = $html;
            $data['pprice'] = !empty($InventoryProducts) ? $InventoryProducts[0]["special_rate"] : 0;
            
            return JsonReturn::success($data);
        }
    }
	
    public function saveOrder(Request $request)
    {
        $status = 0;
        if ($request->ajax())
        {
            $rules = [
				'cart_item_qty' => 'required|array',
				'cart_item_qty.*' => 'required',
				'cart_item_price' => 'required|array',
				'cart_item_price.*' => 'required'
			];

			$input = $request->only(
				'cart_item_qty',
				'cart_item_price'
			);

			$validator = Validator::make($input, $rules);
			if ($validator->fails()) {
				return JsonReturn::error($validator->messages());
			}
			
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}

            $createOrder = InventoryOrders::create([
                'user_id'          => $AdminId,
                'supplier_id'      => ($request->supplier_id) ? $request->supplier_id : '',
                'location_id'      => ($request->location_id) ? $request->location_id : 0,
                'order_date'       => date("Y-m-d H:i:s"),
                'order_total'      => ($request->order_total) ? $request->order_total : 0,
                'order_status'     => 1,
                'order_pdf'        => ($request->order_pdf) ? $request->order_pdf : 0,
				'order_created_by' => Auth::id(),	
                'created_at'       => date("Y-m-d H:i:s"),
                'updated_at'       => date("Y-m-d H:i:s")
            ]); 
            $orderID = $createOrder->id;
			
            if($orderID)
            {
                $cart_item_category_id = $request->cart_item_category_id;
				$cart_item_ids = $request->cart_item_ids;
                $cart_item_qty = $request->cart_item_qty;
                $cart_item_price = $request->cart_item_price;
                if(!empty($cart_item_ids))
                {
                    foreach ($cart_item_ids as $itemKey => $itemId) 
                    {
                        $total_cost = $cart_item_price[$itemKey] * $cart_item_qty[$itemKey];
                        $createOrder = InventoryOrderItems::create([
                            'order_id'      => $orderID,
                            'category_id'   => $cart_item_category_id[$itemKey],
                            'product_id'    => $itemId,
                            'order_qty'     => $cart_item_qty[$itemKey],
                            'received_qty'  => 0,
                            'supply_price'  => $cart_item_price[$itemKey],
                            'total_cost'    => $total_cost,
                            'created_at'    => date("Y-m-d H:i:s"),
                            'updated_at'    => date("Y-m-d H:i:s")
                        ]);   
                    }
                    $status = 1;
					Session::flash('message', 'Purchase order has been created succesfully.');
                    $data["status"] = true;
                    $data["message"] = array("Purchase order has been created succesfully.");
					$data["redirect"] = route('viewOrder',['id' => $orderID]);
                }
            }
            else
            {
                $status = 0;
				$data["status"] = false;
				$data["message"] = array("Something went wrong!");
            }
			
            return JsonReturn::success($data);
        }
    }
	
	public function viewOrder($id = null)
	{
		$InventoryOrders     = array();
		$InventoryOrderItems = array();
		$LocationsData       = array();
		$OrderByUser         = array();
		$OrderSupplier       = array();
		$CancelOrderByUser   = array();
		$ReceivedOrderByUser   = array();
		
		$CurrentUser = User::getUserbyID(Auth::id());
		
        if($id != "") 
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
            $InventoryOrders = InventoryOrders::select('*')->where(['user_id' => $AdminId, 'id'=>$id])->orderBy('id', 'desc')->get()->toArray();
			
			$InventoryOrderItems = InventoryOrderItems::select('inventory_order_items.*','inventory_products.product_name')->join('inventory_products', 'inventory_products.id', '=', 'inventory_order_items.product_id')->where(['inventory_order_items.order_id'=>$id])->orderBy('inventory_order_items.id', 'asc')->get()->toArray();
			
			if(!empty($InventoryOrders))
			{
				$LocationsData = Location::select('id','location_name','location_address')->where('user_id', $AdminId)->where('id', $InventoryOrders[0]['location_id'])->get();
			
				$OrderByUser = User::getUserbyID($InventoryOrders[0]['order_created_by']);
				
				$OrderSupplier = Inventory_supplier::getSupplierbyID($InventoryOrders[0]['supplier_id']);
				
				if($InventoryOrders[0]['order_status'] == 3){
					$CancelOrderByUser = User::getUserbyID($InventoryOrders[0]['order_created_by']);
				} else if($InventoryOrders[0]['order_status'] == 2){
					$ReceivedOrderByUser = User::getUserbyID($InventoryOrders[0]['order_received_by']);
				}
			}
        }
			
		if(empty($InventoryOrders)){
			return redirect('partners/order');	
		}
		
		return view('inventory.viewOrder',compact('InventoryOrders','LocationsData','OrderByUser','InventoryOrderItems','OrderSupplier','CancelOrderByUser','ReceivedOrderByUser','CurrentUser'));
	}
	
	public function receiveOrder($id = null)
	{
		$InventoryOrders     = array();
		$InventoryOrderItems = array();
		$LocationsData       = array();
		$OrderSupplier       = array();
		
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
        if($id != "") 
		{
            $InventoryOrders = InventoryOrders::select('*')->where(['user_id' => $AdminId,'id'=>$id])->orderBy('id', 'desc')->get()->toArray();
			
			$InventoryOrderItems = InventoryOrderItems::select('inventory_order_items.*','inventory_products.product_name')->join('inventory_products', 'inventory_products.id', '=', 'inventory_order_items.product_id')->where(['inventory_order_items.order_id'=>$id])->orderBy('inventory_order_items.id', 'asc')->get()->toArray();
			
			if(!empty($InventoryOrders))
			{
				$LocationsData = Location::select('id','location_name','location_address')->where('user_id', $AdminId)->where('id', $InventoryOrders[0]['location_id'])->get()->toArray();
			
				$OrderSupplier = Inventory_supplier::getSupplierbyID($InventoryOrders[0]['supplier_id']);
			}
        }
			
		if(empty($InventoryOrders)){
			return redirect('partners/order');	
		}
		if($InventoryOrders[0]['order_status'] == 3){
			return redirect('partners/order');
		}
		
		return view('inventory.receiveOrder',compact('InventoryOrders','LocationsData','InventoryOrderItems','OrderSupplier'));
	}
	
	public function receiveSaveOrder(Request $request)
    {
        $status = 0;
        if ($request->ajax())
        {
			$order_id    = $request->order_id;
			$supplier_id = $request->supplier_id;
			$location_id = $request->location_id;
			
			$InventoryOrders = InventoryOrders::find($request->order_id);
			
			$InventoryOrders->order_total  = $request->order_total;
			$InventoryOrders->order_status = 2;
			$InventoryOrders->order_received_by = Auth::id();
			$InventoryOrders->received_at = date("Y-m-d H:i:s");
			$InventoryOrders->updated_at = date("Y-m-d H:i:s");
			
            if($InventoryOrders->save())
            {
				$cart_item_id = $request->cart_item_id;
                $cart_item_category_id = $request->cart_item_category_id;
				$cart_product_id = $request->cart_item_ids;
                $cart_item_qty = $request->cart_item_qty;
                $cart_item_price = $request->cart_item_price;
				
                if(!empty($cart_item_id))
                {
                    foreach ($cart_item_id as $itemKey => $cart_item_id) 
                    {
                        $total_cost = $cart_item_price[$itemKey] * $cart_item_qty[$itemKey];
						
						$InventoryOrderItems = InventoryOrderItems::find($cart_item_id);
						
						$InventoryOrderItems->received_qty = $cart_item_qty[$itemKey];
						$InventoryOrderItems->supply_price = $cart_item_price[$itemKey];
						$InventoryOrderItems->total_cost   = $total_cost;
						$InventoryOrderItems->updated_at = date("Y-m-d H:i:s");
						
						if($InventoryOrderItems->save())
						{
							$InventoryProducts = InventoryProducts::find($cart_product_id[$itemKey]);
							
							$PrevStock = $InventoryProducts->initial_stock;
							$NewStock  = $PrevStock + $cart_item_qty[$itemKey];
							
							$InventoryProducts->initial_stock = $NewStock;
							$InventoryProducts->updated_at    = date("Y-m-d H:i:s");
							if($InventoryProducts->save())
							{
								$InventoryOrderLogs = InventoryOrderLogs::create([
									'item_id'              => $cart_product_id[$itemKey],
									'received_date'        => date("Y-m-d H:i:s"),
									'received_by'          => Auth::id(),
									'location_id'          => $location_id,
									'supplier_id'          => $supplier_id,
									'order_id'             => $order_id,
									'qty_adjusted'         => $cart_item_qty[$itemKey],
									'cost_price'           => $cart_item_price[$itemKey],
									'stock_on_hand'        => $NewStock,
									'enable_stock_control' => $InventoryProducts->enable_stock_control,
									'created_at'           => date("Y-m-d H:i:s"),
									'updated_at'           => date("Y-m-d H:i:s")
								]);
							}
						}
                    }
                    $status = 1;
					Session::flash('message', 'Purchase order has been received succesfully.');
                    $data["status"] = true;
                    $data["message"] = array("Purchase order has been received succesfully.");
					$data["redirect"] = route('orders');
                }
            }
            else
            {
                $status = 0;
				$data["status"] = false;
				$data["message"] = array("Something went wrong!");
            }
			
            return JsonReturn::success($data);
        }
    }
	
	public function cancelOrder(Request $request){
		if ($request->ajax())
        {
			$InventoryOrders = InventoryOrders::find($request->order_id);
			$InventoryOrders->order_status = 3;
			$InventoryOrders->order_cancelled_by = Auth::id();
			$InventoryOrders->cancelled_at = date("Y-m-d H:i:s");
			if($InventoryOrders->save())
			{
				$data["status"]   = true;
				$data["message"]  = array("Order has been cancelled succesfully.");
				$data["redirect"] = route('orders');
				return JsonReturn::success($data);
			}
			else
			{
				$data["status"] = false;
				$data["message"] = array("Order cancellation failed.");
				return JsonReturn::success($data);
			}
				
            return JsonReturn::success($data);
        }
	}
	
	public function sendPurchaseOrderEmail(Request $request)
    {
        $status = 0;
        if ($request->ajax())
        {
			$rules = [
				'order_id' => 'required|numeric',
				'recipient_email_address' => 'required|email',
				'sender_email_address' => 'required|email',
				'message_subject' => 'required',
				'message_content' => 'required'
			];

			$input = $request->only(
				'order_id',
				'recipient_email_address',
				'sender_email_address',
				'message_subject',
				'message_content'
			);

			$validator = Validator::make($input, $rules);
			if ($validator->fails()) {
				return JsonReturn::error($validator->messages());
			}
			
			$LocationName = '';
			$InventoryOrders = InventoryOrders::select('*')->where(['id'=>$request->order_id])->orderBy('id', 'desc')->get()->toArray();
			if(!empty($InventoryOrders)){
				$LocationsData = Location::select('location_name')->where('id', $InventoryOrders[0]['location_id'])->get()->toArray();	
				$LocationName = ($LocationsData[0]['location_name']) ? ($LocationsData[0]['location_name']) : 'Purchase Order';
			}
			
			$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
			$FROM_NAME      = ($LocationName) ? $LocationName : 'Purchase Order';
			$TO_EMAIL       = ($request->recipient_email_address) ? $request->recipient_email_address : '';
			$CC_EMAIL       = ($request->sender_email_address) ? $request->sender_email_address : 'Purchase Order';
            $REPLY_TO       = ($request->sender_email_address) ? $request->sender_email_address : env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
			$SUBJECT        = ($request->subject) ? $request->subject : 'Purchase Order';
			$MESSAGE        = ($request->message_content) ? $request->message_content : 'Hi  Please see attached purchase order Have a great day! ';
			$OrderId        = ($request->order_id) ? Crypt::encryptString($request->order_id) : 0;
			
			$SendMail = Mail::to($TO_EMAIL)->send(new PurchaseOrder($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$OrderId,$REPLY_TO));	
			// ->cc([$CC_EMAIL])
			$data["status"] = true;
			$data["message"] = "Order Mail has been sent succesfully.";	
			
            return JsonReturn::success($data);
        }
    }
	
    private function unique_code($digits)
    {
        $this->autoRender = false;
        srand((double)microtime() * 10000000);
        $input = array("A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        //$input = array("0","1","2","3","4","5","6","7","8","9");
        $random_generator = "";// Initialize the string to store random numbers
        for ($i = 1; $i < $digits + 1; $i++) {
            // Loop the number of times of required digits

            if (rand(1, 2) == 1) {// to decide the digit should be numeric or alphabet
                $rand_index = array_rand($input);
                $random_generator .= $input[$rand_index]; // One char is added
            } else {
                $random_generator .= rand(1, 7); // one number is added
            }
        } // end of for loop
        return $random_generator;
    }
	
	public function generatePDF($id = null)
    {
		if($id != '')
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$InventoryOrders = InventoryOrders::select('*')->where(['user_id' => $AdminId,'id'=>$id])->orderBy('id', 'desc')->get()->toArray();
			
			$InventoryOrderItems = InventoryOrderItems::select('inventory_order_items.*','inventory_products.product_name','inventory_products.barcode')->join('inventory_products', 'inventory_products.id', '=', 'inventory_order_items.product_id')->where(['inventory_order_items.order_id'=>$id])->orderBy('inventory_order_items.id', 'asc')->get()->toArray();
			
			if(!empty($InventoryOrders))
			{
				$LocationsData = Location::select('id','location_name','location_address')->where('user_id', $AdminId)->where('id', $InventoryOrders[0]['location_id'])->get()->toArray();
			
				$OrderSupplier = Inventory_supplier::getSupplierbyID($InventoryOrders[0]['supplier_id']);
			}
			
			$pdfData = array();
			$pdfData['InventoryOrders']     = $InventoryOrders;
			$pdfData['InventoryOrderItems'] = $InventoryOrderItems;
			$pdfData['LocationsData']       = $LocationsData;
			$pdfData['OrderSupplier']       = $OrderSupplier;
		
			$fileName = ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0;
		
			return PDF::loadView('pdfTemplates.purchaseOrder',$pdfData)->setPaper('a4')->download('P'.$fileName.'.pdf');
		}
    }
	
	public function saveOrderPdf($id = null)
    {
		if($id != '')
		{
			try {
				$orderId = Crypt::decryptString($id);
				
				$InventoryOrders = InventoryOrders::select('*')->where(['id'=>$orderId])->orderBy('id', 'desc')->get()->toArray();
				
				$InventoryOrderItems = InventoryOrderItems::select('inventory_order_items.*','inventory_products.product_name','inventory_products.barcode')->join('inventory_products', 'inventory_products.id', '=', 'inventory_order_items.product_id')->where(['inventory_order_items.order_id'=>$orderId])->orderBy('inventory_order_items.id', 'asc')->get()->toArray();
				
				if(!empty($InventoryOrders))
				{
					$LocationsData = Location::select('id','location_name','location_address')->where('id', $InventoryOrders[0]['location_id'])->get()->toArray();
				
					$OrderSupplier = Inventory_supplier::getSupplierbyID($InventoryOrders[0]['supplier_id']);
				}
				
				$pdfData = array();
				$pdfData['InventoryOrders']     = $InventoryOrders;
				$pdfData['InventoryOrderItems'] = $InventoryOrderItems;
				$pdfData['LocationsData']       = $LocationsData;
				$pdfData['OrderSupplier']       = $OrderSupplier;
			
				$fileName = ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0;
			
				return PDF::loadView('pdfTemplates.purchaseOrder',$pdfData)->setPaper('a4')->download('P'.$fileName.'.pdf');
			} catch (DecryptException $e) {
				return redirect()->route('/');
			}
		}
    }
}
