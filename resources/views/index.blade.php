<style>
.done {
    text-decoration: line-through;
    color: red;
}
</style>
<h1>ONEX-CRM</h1>
Auth -> guards
user table -> default (Auth::user()) --- CLIENTS<br/> 
admin table -> Auth::gaurd('admin')->user() --- ADMINISTRATOR<br/> 
customer table -> Auth::gaurd('customer')->user() --- CUSTOMER OF CLIENTS (from store)<br/>
<hr/>
<h3>Tasks</h3>
<ul>
<ol class="done">Setup initial design ui level</ol>
<ol class="done">Multi lang setup</ol>
<ol>vue js setup</ol>
<ol>vuelidate setup</ol>
<ol>user migration</ol>
<ol>user type seeder - owner, employee</ol>
<ol>user role seeder - super-admin (default owner)</ol>
<ol>Signup process</ol>
</ul>