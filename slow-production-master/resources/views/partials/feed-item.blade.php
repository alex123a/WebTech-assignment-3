<div class="box post"> <!-- Do not remove the post class -->
    <article class="media">
        <figure class="media-left">
            <p class="image is-64x64">
                <img class="is-rounded"
                    src="{{ asset('img/avatar.png')  }}">
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <p>
                    <strong>{{ $feedItem->user->name }}</strong><br><small>{{ $feedItem->user->email }}</small>
                    <small>{{ $feedItem->created_at->diffForHumans() }}</small>
                    <br>
                    {{ $feedItem->content }}
                </p>
            </div>
            <div class="is-flex">
                <div class="is-flex is-flex-grow-1">

                        <!-- 
                            Task 1 Authorization:
                            Like or dislike are only available for logged users
                        -->
                        <!-- 
                            Task 4 User, step 4:
                            add the HTTP method and url as instructed
                        -->
                        <!-- 
                            Task 5 User, step 3:
                            Add/Remove this form as instructed
                        -->
                        <?php
                        if(auth()->check()) {
                            // print($post->likes);
                            if(!(auth()->user()->hasUserLiked($post))){?>
                                <form method="POST" action="{{route('posts.like', [$feedItem])}}">
                                    <?php echo csrf_field() ?>
                                    <a onclick="this.closest('form').submit();return false;" class="has-text-danger mr-2 like ">
                                        <span>
                                            <span class="icon is-small">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                    style="fill: currentColor;">
                                                    <path
                                                        d="M20.205 4.791a5.938 5.938 0 0 0-4.209-1.754A5.906 5.906 0 0 0 12 4.595a5.904 5.904 0 0 0-3.996-1.558 5.942 5.942 0 0 0-4.213 1.758c-2.353 2.363-2.352 6.059.002 8.412L12 21.414l8.207-8.207c2.354-2.353 2.355-6.049-.002-8.416z"></path>
                                                </svg>
                                            </span>
                                            <span>Like</span>
                                        </span>
                                    </a>
                                </form>
                        <?php
                            } else { ?>
                                <form method="POST" action="{{route('posts.dislike', [$feedItem])}}">
                                    <?php echo csrf_field() ?>
                                    <a onclick="this.closest('form').submit();return false;" class="has-text-danger mr-2 dislike">
                                        <span>Dislike</span>
                                    </a>
                                </form>
                                <?php
                            }   
                        }
                        ?>
                                
                        


                        <!-- 
                            Task 5 User, step 5:
                            add the HTTP method and url as instructed
                        -->
                        <!-- 
                            Task 5 User, step 4:
                            Remove this form as instructed
                        -->
                        

                    <p>
                        <b>{{ $feedItem->likes_count }}</b> people like this
                    </p>
                </div>

                <!-- 
                    Task 1 Authorization:
                    Delete should never appear for guest users
                -->
                <!-- 
                    Task 6 User, step 5:
                    add the HTTP method and url as instructed
                -->
                <!-- 
                    Task 6 User, step 3, 4:
                    This form should only be available for the user who created this post
                -->
                <!-- 
                    Task 3 Moderator, step 5:
                    add the HTTP method and url as instructed
                -->
                <!-- 
                    Task 3 Moderator, step 4:
                    For a moderator, this form is always available
                -->
                @if(auth()->check() && (auth()->user()->id == $post["user_id"] || auth()->user()->role == "moderator" ) )
                    <form method="POST" action="{{route('posts.delete', [$feedItem])}}">
                        <?php echo csrf_field(); ?>
                        @method("delete")

                        <a onclick="this.closest('form').submit();return false;" class="has-text-grey delete-post">
                            Delete
                        </a>
                    </form>
                @endif
            </div>
        </div>
    </article>
</div>
