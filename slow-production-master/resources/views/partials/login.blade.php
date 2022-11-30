<!-- 
    Task 3 Guest, step 4: 
    add the HTTP method and url as instructed
-->
<form action="<?php echo route('doLogin') ?>" method="POST" class="box login-panel">
    <?php echo csrf_field() ?>
    <h5 class="is-5 title has-text-centered">Login</h5>
    <div class="field">
        <label class="label">Email</label>
        <div class="control">
            <input class="input email" type="email" name="email" value="{{ old('email') }}">
        </div>
        @error('email')
        <p class="help is-danger">{{ $errors->first('email') }}</p>
        @enderror
    </div>
    <div class="field">
        <label class="label">Password</label>
        <div class="control">
            <input class="input password" type="password" name="password" value="{{ old('password') }}">
        </div>
        @error('password')
        <p class="help is-danger">{{ $errors->first('password') }}</p>
        @enderror
    </div>
        <!-- 
            Task 3 Guest, step 2: 
            add login fields as instructed
            
            Tip: 
            you can use the same style as the registration form
        -->
        
    <div class="field">
        <button class = "button is-primary is-fullwidth login-submit" type = "submit">Login</button>
        <!-- 
            Task 3 Guest, step 3:
            add submit button

            Tip:
            you can use the class "button is-primary is-fullwidth" for the appearance of the button
        -->
    </div>
</form>
