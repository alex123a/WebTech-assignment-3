<div class="box logged-in-panel"> <!-- dont remove the logged-in-panel class from this form -->
    <!-- 
        Task 1 User, step 1: 
        add name of logged user
    -->
    <h4 class="is-4 title has-text-centered user-name">{{auth()->user()->name}}</h4>
    <h5 class="is-5 subtitle has-text-centered">Logged in</h5>
    <nav class="level">
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Posts</p>
                <p class="title">{{ auth()->user() ? auth()->user()->posts()->count() : 0 }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Likes received</p>
                <p class="title">{{ auth()->user()? auth()->user()->likesReceived()->count() : 0}}</p>
            </div>
        </div>
    </nav>
</div>
