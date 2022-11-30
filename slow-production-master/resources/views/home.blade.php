@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-8">
                    @isset($title)
                        <h2 class="title is-2">{{ $title }}</h2>
                    @endisset

                    <!-- 
                        Task 1 Guest, step 3:
                        The partial `create-post` should not appear if a guest user is navigating this page
                    -->
                    <!-- 
                        Task 3 User, step 3:
                        The partial `create-post` should appear if a logged user is navigating this page
                    -->
                    @if(auth()->check())
                        @include('partials.create-post')
                    @endif

                    <div class="columns">
                        <div class="column is-8 is-offset-2">
                            @forelse($feed as $feedItem)
                                @include('partials.feed-item', ['post' => $feedItem])
                            @empty
                                This list seems to be empty
                            @endforelse
                        </div>
                    </div>
                    {{ $feed->links() }}
                </div>
                <div class="column is-4">

                        <!-- 
                            Task 1 Authorization
                            The right partial should appear depending if the user is logged in or not
                            logged-in for logged users
                            login for guest users
                        -->
                        
                        <!-- 
                            Task 1 User, step 2
                            The partial logged-in should only be visible to authenticated users
-->                     @if(auth()->check()) 
                            @include('partials.logged-in')
                        @endif
                        <!-- 
                            Task 1 User, step 3
                            The partial login should only be visible to guest users
                        -->
                        @if (!auth()->check())
                            @include('partials.login')
                        @endif

                    @if(session()->has('success'))
                        <article class="message is-success">
                            <div class="message-header">
                                <p>Success</p>
                            </div>
                            <div class="message-body">
                                {{ session('success') }}
                            </div>
                        </article>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
