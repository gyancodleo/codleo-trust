<p>Hello {{ $user->name }},</p>

<p>Your Codleo Trust account has been created. Below are your login details:</p>

<p><strong>Login URL:</strong> {{ url('/login') }}</p>
<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>Password:</strong> {{ $password }}</p>

<p>You can login now to our codleo trust portal.</p>

<p>Regards,<br>Codleo Trust System</p>