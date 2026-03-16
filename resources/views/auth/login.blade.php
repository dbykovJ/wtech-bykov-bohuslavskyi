@extends('layouts.auth')

@section('title', 'Login')
@section('content')
    <section class="w-full h-full flex flex-col items-center justify-center gap-[55px]">
    <section class="flex content-center justify-start flex-col gap-[53px] w-[400px] h-[400px]">
        <h1 class="w-full text-center font-inter text-[50px] font-bold">Login</h1>
        <form method="POST" action="#" class="w-full flex flex-col items-center justify-start gap-[56px]">
            @csrf
            <div class="flex flex-col gap-[18px] w-full">
                <div>
                    <input class="w-full h-[40px] border-[2px] border-[#252526] rounded-[20px] font-inter font-medium text-[15px] placeholder:text-[#000000]:opacity-[70%] px-[10px]" id="email" type="email" name="email" placeholder="Username" value="{{ old('email') }}" required autofocus>
                </div>
                <div>
                    <input class="w-full h-[40px] border-[2px] border-[#252526] rounded-[20px] font-inter font-medium text-[15px] placeholder:text-[#000000]:opacity-[70%] px-[10px]" id="password" type="password" name="password" placeholder="Password" required>
                </div>
            </div>

            <button class="w-[158px] h-[38px] text-center font-inter text-[15px] bg-[#4C4848] text-white rounded-[20px] uppercase font-medium" type="submit">Login</button>
        </form>
    </section>
    <div class="w-full text-center font-body text-[16px] text-[#111111]">
        Don't have an account? <a href="{{ route('register') }}" class="text-[#085776] font-medium">Register.</a>
    </div>  
    </section>
@endsection
