<?php include 'header.php' ?>

<div class="page-header">
<h1>Rusic Webhooks <small>Lyris</small></h1>
</div>

<p><a href="../">&laquo; Back</a></p>

<p>Lyris is a campaign management product. Check out <a href="http://www.lyris.com/">http://www.lyris.com/</a></p>

<p>The webhook for lyris should look like:</p>

<pre>
http://hooks.rusic.com/lyris?siteid=1234&listid=999&pass=PASSWORD&emailsource=email&forenamesource=forename&surnamesource=surname&ridtarget=1111
</pre>

<p><strong>Note.</strong> The ID of the rusic entry will be placed in a merge field called RID. You need to add this to the lyris list you are adding subscribers to. This ID can then be used for such things as linking to entry etc.</p>

<p>The get parameters are as follows (items in [] are optional).</p>

<ul>
    <li><strong>siteid</strong> - the lyris siteid</li>
	<li><strong>listid</strong> - the lyris list you wish to put contacts on to.</li>
    <li><strong>pass</strong> - the lyris password</li>
	<li><strong>emailsource</strong> - the POST field to use for the email address.</li>
    <li>[<strong>forenamesource</strong>] - the post field to use as forename.</a></li>
    <li>[<strong>surnamesource</strong>] - the post field to use as surname.</a></li>
	<li>[<strong>ridtarget</strong>] - which field (numeric) to put rusic entry id in to.</a></li>
	<li>[<strong>custom1source</strong>] - which field holds data for custom 1.</a></li>
	<li>[<strong>custom1target</strong>] - which field to put custum1 in to.</a></li>
	<li>[<strong>custom2source</strong>] - which field holds data for custom 2.</a></li>
	<li>[<strong>custom2target</strong>] - which field to put custum1 in to.</a></li>
	<li>[<strong>custom3source</strong>] - which field holds data for custom 3.</a></li>
	<li>[<strong>custom3target</strong>] - which field to put custum1 in to.</a></li>
	<li>[<strong>custom4source</strong>] - which field holds data for custom 4.</a></li>
	<li>[<strong>custom4target</strong>] - which field to put custum1 in to.</a></li>
	<li>[<strong>custom5source</strong>] - which field holds data for custom 5.</a></li>
	<li>[<strong>custom5target</strong>] - which field to put custum1 in to.</a></li>		
</ul>

<h2>Web Hook Test Form</h2>

<script type="text/javascript">
function OnSubmitForm()
{
  
}

function UpdatePostAction()
{
	document.testForm.actionField.value = '/lyris?siteid='+document.testForm.siteid.value
		+'&listid='+document.testForm.listid.value
		+'&pass='+document.testForm.pass.value
		+'&emailsource='+document.testForm.emailsource.value
		+'&forenamesource='+document.testForm.forenamesource.value
		+'&surnamesource='+document.testForm.surnamesource.value
		+'&ridtarget='+document.testForm.ridtarget.value;
		
	document.testForm.action = document.testForm.actionField.value;
	
}
</script>

<form name="testForm" action="/lyris" method="POST">
	
	<fieldset>
		
		<dl>
			<dt><label for="siteid">SiteID</label></dt>
			<dd><input type="text" id="siteid" name="siteid" onkeyup="UpdatePostAction()"></dd>
			
			<dt><label for="siteid">ListID</label></dt>
			<dd><input type="text" id="listid" name="listid" onkeyup="UpdatePostAction()"></dd>
			
			<dt><label for="pass">Password</label></dt>
			<dd><input type="text" id="pass" name="pass" onkeyup="UpdatePostAction()"></dd>
			
			<dt><label for="emailsource">Email Source Field</label></dt>
			<dd><input type="text" id="emailsource" name="emailsource" onkeyup="UpdatePostAction()" value="email"></dd>
			
			<dt><label for="email">Email Address</label></dt>
			<dd><input type="text" id="email" name="email"></dd>

			<dt><label for="forenamesource">Forename Source Field</label></dt>
			<dd><input type="text" id="forenamesource" name="forenamesource" onkeyup="UpdatePostAction()" value="forename"></dd>
			
			<dt><label for="forename">Forename</label></dt>
			<dd><input type="text" id="forename" name="forename"></dd>
			
			<dt><label for="surnamesource">Surname Source Field</label></dt>
			<dd><input type="text" id="surnamesource" name="surnamesource" onkeyup="UpdatePostAction()" value="surname"></dd>
			
			<dt><label for="surname">Surname</label></dt>
			<dd><input type="text" id="surname" name="surname"></dd>
			
			<dt><label for="ridtarget">Rusic ID Target Field (should be numeric field id as in lyris)</label></dt>
			<dd><input type="text" id="ridtarget" name="ridtarget" onkeyup="UpdatePostAction()"></dd>
			
			<dt><label for="id">Rusic ID</label></dt>
			<dd><input type="text" id="id" name="id"></dd>
			
			<dt><label for="surname">Custom1 Data</label></dt>
			<dd><input type="text" id="custom1data" name="custom1data"></dd>
			
		</dl>
		
		<h3>Form will be posted to</h3>
		
		<input type="text" name="actionField" value="" style="width:800px">
		<br>
		<input type="submit" value="POST">
		
	</fieldset>
</form>

<?php include 'footer.php' ?>