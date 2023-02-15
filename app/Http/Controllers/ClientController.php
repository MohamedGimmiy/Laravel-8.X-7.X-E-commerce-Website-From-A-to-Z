<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Models\Order;
use Stripe\Charge;
use Stripe\Stripe;
use App\Mail\SendMail;
use App\Models\Client;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    public function home()
    {
        $sliders = Slider::all()->where('status',1);
        $products = Product::all()->where('status',1);
        return view('client\home',compact('sliders','products'));
    }
    public function create_account(Request $request){
        $validated = $request->validate([
            'email' => 'required|unique:clients|email',
            'password' => "required|min:4"
        ]);
        Client::create([
            'email' => $validated['email'],
            'password' => bcrypt($validated['password'])
        ]);

        return back()->with('success','You Account Created successfully!');

    }


    public function access_account(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => "required|min:4"
        ]);

        $client = Client::where('email', $validated['email'])->first();

        if($client){
            if(Hash::check($validated['password'], $client->password)){
                session()->put('client', $client);
                return redirect('/shop');
            }else{
                return back()->with('status', 'Wrong email or password!');
            }
        }else{
            return back()->with('status', 'You do not have an account with this email!');
        }

    }

    public function logout(){
        session()->forget('client');
        return redirect('/shop');
    }
    public function shop(){
        $categories = Category::all();
        $products = Product::all()->where('status',1);
        return view('client\shop',compact('categories','products'));
    }
    public function cart(){
        if(!Session::has('cart')){
            return view('client.cart');
        }
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);

        return view('client\cart',[
            'products' => $cart->items
        ]);
    }
    public function addtocart($id){
        $product = Product::findOrFail($id);
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        Session::put('cart', $cart);

        //dd(Session::get('cart'));
        return redirect('/cart');
    }

    public function update_qty(Request $request, $id){
                //print('the product id is '.$request->id.' And the product qty is '.$request->quantity);
                $oldCart = Session::has('cart')? Session::get('cart'):null;
                $cart = new Cart($oldCart);
                $cart->updateQty($id, $request->quantity);
                Session::put('cart', $cart);

                //dd(Session::get('cart'));
                return back();
    }

    public function remove_from_cart($id){
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);

        if(count($cart->items) > 0){
            Session::put('cart', $cart);
        }
        else{
            Session::forget('cart');
        }

        //dd(Session::get('cart'));
        return back();
    }
    public function checkout(){
        if(!Session::has('client')){
            return view('client\login');
        }
        if(!Session::has('cart')){
            return view('client\cart');
        }
        return view('client\checkout');
    }
    public function login(){
        return view('client\login');
    }
    public function signup()
    {
        return view('client\signup');
    }

    public function postcheckout(Request $request){
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);



    Stripe::setApiKey('sk_test_51M71dhLlETGUHA9P8wDaFbHShxxmC7sQiRv2Ij0rIGZXurBDexukQu05LSvSb2FqRSlrCPXCAtx1i9lINRxXMVOq00G9I8zxla');

    try{

        $charge = Charge::create(array(
            "amount" => $cart->totalPrice * 100,
            "currency" => "usd",
            "source" => $request->input('stripeToken'), // obtainded with Stripe.js
            "description" => "Test Charge"
        ));



    } catch(\Exception $e){
        Session::put('error', $e->getMessage());
        return redirect('/checkout');
    }

        $buyer_id = time();
        Order::create([
            'name' => $request->name,
            'address' => $request->address,
            'cart' => serialize($cart),
            'buyer_id'=> $buyer_id
        ]);

        session()->forget('cart');

        $orders = Order::where('buyer_id',$buyer_id)->get();
        $orders->transform(function($order, $key){
            $order->cart = unserialize($order->cart);
            return $order;
        });

        //send an email
        $email = session()->get('client')->email;

        Mail::to($email)->send(new SendMail($orders));

        return redirect('/cart')->with('status','Your purchase has been successfully accomplished !!');
    }
    public function orders(){
        $orders = Order::all();
        $orders->transform(function($order, $key){
            $order->cart = unserialize($order->cart);
            return $order;
        });
        return view('admin\orders',compact('orders'));
    }


}
