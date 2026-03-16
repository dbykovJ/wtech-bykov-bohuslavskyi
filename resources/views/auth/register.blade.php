@extends('layouts.auth')

@section('title', 'Register')
@section('content')
    <section class="w-full h-full flex flex-col items-center align-center justify-center">
        <section
            class="flex content-center justify-start flex-col gap-[53px] w-[400px] h-fit border-[#252526] border-[1px] rounded-[10px] px-[24px] pt-[36px] pb-[24px]">
            <h1 class="w-full text-center font-inter text-[50px] font-bold">Register</h1>
            <form method="POST" action="#" class="w-full flex flex-col items-center justify-start gap-[56px]">
                @csrf
                <div class="flex flex-col gap-[18px] w-full">
                    <div>
                        <input
                            class="w-full h-[40px] border-[2px] border-[#252526] rounded-[10px] font-inter font-medium text-[15px] placeholder:text-[#000000]:opacity-[70%] px-[10px]"
                            id="name" type="text" name="name" placeholder="Full Name" value="{{ old('name') }}"
                            required autofocus>
                    </div>
                    <div>
                        <input
                            class="w-full h-[40px] border-[2px] border-[#252526] rounded-[10px] font-inter font-medium text-[15px] placeholder:text-[#000000]:opacity-[70%] px-[10px]"
                            id="phone" type="text" name="phone" placeholder="Phone" value="{{ old('phone') }}"
                            required autofocus>
                    </div>
                    <div>
                        <input
                            class="w-full h-[40px] border-[2px] border-[#252526] rounded-[10px] font-inter font-medium text-[15px] placeholder:text-[#000000]:opacity-[70%] px-[10px]"
                            id="password" type="password" name="password" placeholder="Password" required>
                    </div>
                    <div>
                        <input
                            class="w-full h-[40px] border-[2px] border-[#252526] rounded-[10px] font-inter font-medium text-[15px] placeholder:text-[#000000]:opacity-[70%] px-[10px]"
                            id="confirm-password" type="password" name="password_confirmation"
                            placeholder="Confirm Password" required>
                    </div>
                </div>

                <section class="w-full flex flex-col gap-[15px]">
                    <button
                        class="w-full h-[38px] text-center font-inter text-[15px] bg-[#7F7D83] text-white rounded-[20px] font-medium"
                        type="submit">Register
                    </button>
                    <button
                        class="w-full h-[38px] text-center font-inter text-[15px] bg-[#4C4848] text-white rounded-[20px] font-medium"
                        type="submit">Have an account? Sign In
                    </button>
                </section>
            </form>
        </section>
    </section>
@endsection
