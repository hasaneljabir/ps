<?php

/** 
*
* @author       : Hasan El Jabir <eljabirhasan@gmail.com>
* @copyright    : Gunadarma University Computing Center
* @contact      : 0812 8923 3370 
*
**/

namespace App\Http\Controllers;

use Auth; //Laravel Auth
use App\Role; //Model role
use App\User; //Model user
use Illuminate\Http\Request; //Library untuk request input
use App\Http\Controllers\Controller; //Controller Laravel 

class UserController extends Controller
{
    
    //Fungsi yang otomatis dijalankan saat controller dipanggil 
    public function __construct()
    {
        //Autentifikasi
        $this->middleware('auth');
    }

    //Fungsi default yang dipanggil 
    public function index() 
    {
        //Nomor urut data pada view
        $number = 0;

        //Mengambil daftar user dari tabel users
    	$data = User::where('id_role', '=', 3)->orderBy('id', 'DESC')->get();

        //Menampilkan daftar user ke view
        return view('admin.user.index', compact('data', 'number'));
    }

    //Fungsi menampilkan view tambah user
    public function create()
    {
        //Menampilkan view tambah user
        return view('admin.user.tambah', compact('role'));
    }

    //Fungsi menyimpan user baru
    public function store(Request $request)
    {
        //Validasi form
        $this->validate($request, [
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        //Mengambil semua data input
        $input = [
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')), //Enkripsi password
            'id_role' => 3,
        ];

        //Menyimpan data user ke tabel users
        User::create($input); 

        //Redirect ke halaman indeks user
        return redirect()->route('user.index')
            ->with('feedback','<div class="alert alert-success"><p>User berhasil ditambah</p></div>');
    }

    //Fungsi menampilkan view edit user
    public function edit($id)
    {
        //Ambil data user yang dipilih dari tabel users
        $data = User::find($id);

        //Menampilkan form edit
        return view('admin.user.ubah',compact('data'));
    }

    //Fungsi update user
    public function update(Request $request, $id)
    {
        //Ambil username dan email lama
        $username_lama = User::where('id', $id)->first()->username;
        $email_lama = User::where('id', $id)->first()->email;

        //Ambil username dan email inputan
        $username = $request->input('username');
        $email = $request->input('email');
        
        //Input jika username dan email masih sama
        if($username==$username_lama && $email==$email_lama)
        {
            //Validasi form
            $this->validate($request, [
                'name' => 'required|max:255',
                'password' => 'required|min:6|confirmed', //Enkripsi Password
            ]);

            //Definisi inputan
            $input = [
                'name' => $request->input('name'),
                'password' => bcrypt($request->input('password')), //Enkripsi password
                'id_role' => $request->input('id_role'),
            ];
        }
        //Input jika username masih sama
        else if($username==$username_lama) 
        {
            //Validasi form
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);

            //Definisi inputan
            $input = [
                'name' => $request->input('name'),
                'email' => $email,
                'password' => bcrypt($request->input('password')), //Enkripsi password
                'id_role' => $request->input('id_role'),
            ];
        }
        //Input jika email masih sama
        else if($email==$email_lama) 
        {
            //Validasi form
            $this->validate($request, [
                'name' => 'required|max:255',
                'username' => 'required|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);

            //Definisi inputan
            $input = [
                'name' => $request->input('name'),
                'username' => $username,
                'password' => bcrypt($request->input('password')), //Enkripsi password
                'id_role' => $request->input('id_role'),
            ];
        }
        //Input jika username dan email diganti
        else
        {
            //Validasi form
            $this->validate($request, [
                'name' => 'required|max:255',
                'username' => 'required|max:255|unique:users',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);
            //Definisi inputan
            $input = [
                'name' => $request->input('name'),
                'username' => $username,
                'email' => $email,
                'password' => bcrypt($request->input('password')), //Enkripsi password
                'id_role' => $request->input('id_role'),
            ];
        }

        //Update data user
        User::find($id)->update($input);    
        
        //Redirect ke halaman indeks user
        return redirect()->route('user.index')
            ->with('feedback','<div class="alert alert-success"><p>User berhasil di update</p></div>');
    }

    //Fungsi hapus user
    public function destroy($id)
    {
        //Ambil id user yang sedang log in
        $logged_in = Auth::user()->id;

        //Jika user sedang login
        if($id==$logged_in)
        {
            //Redirect ke halaman indeks user
            return redirect()->route('user.index')
                ->with('feedback','<div class="alert alert-danger"><p>GAGAL - USER SEDANG LOGIN !</p></div>');
        }

        //Hapus user dari tabel users
        User::find($id)->delete();

        //Redirect ke halaman indeks user
        return redirect()->route('user.index')
            ->with('feedback','<div class="alert alert-success"><p>User berhasil dihapus</p></div>');
    }

}