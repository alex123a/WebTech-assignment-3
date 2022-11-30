<div class="columns mb-6">
    <div class="column is-10 is-offset-1">
        <form action="{{ route('posts.store') }}" method="post" class="box">
            @csrf
            <h5 class="title is-5">Share an update</h5>
            <article class="media">
                <figure class="media-left">
                    <p class="image is-64x64">
                        <img class="is-rounded" src="{{ asset('img/default-avatar.jpg') }}">
                    </p>
                </figure>
                <div class="media-content">
                    <div class="field">
                        <p class="control">
                            <textarea class="textarea" name="content"
                                      placeholder="What are you up to right now?">{{ old('content') }}</textarea>
                        </p>
                        @error('content')
                        <p class="help is-danger">{{ $errors->first('content') }}</p>
                        @enderror
                    </div>
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <button type="submit" class="button is-info  submit-post">Create post</button>
                            </div>
                        </div>
                    </nav>
                </div>
            </article>
        </form>
    </div>
</div>
