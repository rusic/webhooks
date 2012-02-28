<?php include 'header.php' ?>

<div class="page-header">
<h1>Rusic Webhooks <small>Mailchimp</small></h1>
</div>

<p><a href="../">&laquo; Back</a></p>

<p>MailChimp is a campaign management product. Check out <a href="http://mailchimp.com/">http://mailchimp.com/</a></p>

<p>The webhook for mailchimp should look like:</p>

<pre>
http://hooks.rusic.com/mailchimp?email=custom1&listid=123&apikey=abz
</pre>

<p><strong>Note.</strong> The ID of the rusic entry will be placed in a merge field called RID. You need to add this to the mailchimp list you are adding subscribers to. This ID can then be used for such things as linking to entry etc.</p>

<p>The get parameters are as follows (items in [] are optional).</p>

<ul>
    <li><strong>email</strong> - the POST field to use for the email address.</li>
    <li><strong>listid</strong> - the mailchimp list you wish to put contacts on to.</li>
    <li><strong>apikey</strong> - the mailchimp API key <a href="http://kb.mailchimp.com/article/where-can-i-find-my-api-key">see mailchimp.</a></li>
    <li>[<strong>forename</strong>] - the post field to use as forename.</a></li>
    <li>[<strong>surname</strong>] - the post field to use as surname.</a></li>
    <li>[<strong>doubleoptin</strong>] - set to true to use a double opt-in process.</a></li>
</ul>

<?php include 'footer.php' ?>