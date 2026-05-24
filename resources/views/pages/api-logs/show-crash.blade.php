@extends('layouts.app')

@section('content')
    <div class="space-y-5">

        {{-- HEADER --}}
        <div class="bg-white rounded-2xl shadow p-6">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <div class="flex items-center gap-3">

                        <span
                            class="
                        text-xs font-medium px-3 py-1 rounded-full
                        bg-red-100 text-red-600
                    ">

                            {{ $log->status_code }}

                        </span>

                        <h1 class="text-2xl font-bold text-gray-800">
                            Crash Log Detail
                        </h1>

                    </div>

                    <p class="text-sm text-gray-500 mt-2">
                        {{ $log->created_at->format('d M Y H:i:s') }}
                    </p>

                </div>

                <a href="{{ route('api-logs.crashes') }}"
                    class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-black transition">

                    Kembali

                </a>

            </div>

        </div>

        {{-- DETAIL --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- LEFT --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- ERROR MESSAGE --}}
                <div class="bg-white rounded-2xl shadow p-6">

                    <h2 class="font-bold text-lg text-gray-800 mb-4">
                        Error Message
                    </h2>

                    <div
                        class="
                    bg-red-50
                    border border-red-100
                    rounded-xl
                    p-4
                    text-sm
                    text-red-700
                    break-words
                ">

                        {{ $log->message }}

                    </div>

                </div>

                {{-- STACK TRACE --}}
                <div class="bg-white rounded-2xl shadow p-6">

                    <h2 class="font-bold text-lg text-gray-800 mb-4">
                        Stack Trace
                    </h2>

                    <div
                        class="
                    bg-gray-950
                    rounded-xl
                    p-5
                    overflow-x-auto
                ">

                        <pre
                            class="
                        text-xs
                        text-green-400
                        whitespace-pre-wrap
                        break-words
                    ">{{ $log->trace }}</pre>

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="space-y-5">

                {{-- META --}}
                <div class="bg-white rounded-2xl shadow p-6">

                    <h2 class="font-bold text-lg text-gray-800 mb-4">
                        Request Info
                    </h2>

                    <div class="space-y-4 text-sm">

                        <div>

                            <p class="text-gray-500">
                                Method
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $log->method }}
                            </p>

                        </div>

                        <div>

                            <p class="text-gray-500">
                                URL
                            </p>

                            <p class="font-semibold text-gray-800 break-all">
                                {{ $log->url }}
                            </p>

                        </div>

                        <div>

                            <p class="text-gray-500">
                                User
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $log->user?->name ?? '-' }}
                            </p>

                        </div>

                        <div>

                            <p class="text-gray-500">
                                IP Address
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $log->ip }}
                            </p>

                        </div>

                        <div>

                            <p class="text-gray-500">
                                Request ID
                            </p>

                            <p class="font-semibold text-gray-800 break-all">
                                {{ $log->request_id }}
                            </p>

                        </div>

                    </div>

                </div>

                {{-- REQUEST BODY --}}
                <div class="bg-white rounded-2xl shadow p-6">

                    <h2 class="font-bold text-lg text-gray-800 mb-4">
                        Request Body
                    </h2>

                    <div
                        class="
                    bg-gray-100
                    rounded-xl
                    p-4
                    overflow-x-auto
                ">

                        <pre
                            class="
                        text-xs
                        text-gray-700
                        whitespace-pre-wrap
                        break-words
                    ">@json($log->request_body, JSON_PRETTY_PRINT)</pre>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
