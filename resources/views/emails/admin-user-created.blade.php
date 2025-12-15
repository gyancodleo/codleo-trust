<p>Hello {{ $user->name }},</p>

<p>Your admin account has been created. Below are your login details:</p>

<p><strong>Login URL:</strong> {{ url('/admin/login') }}</p>
<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>Password:</strong> {{ $password }}</p>

<p>You can now login to codleo trust using your credential.</p>

<p>Regards,<br>Codleo Trust System</p>

