<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="./js/printThis.js"></script>

<script>
function saveimg()
{
    $("#mycanvas").printThis();
}
</script>
<div id="mycanvas">
This is just a test<br />
12344<br />
</div>

<button onclick="saveimg()">Save</button>