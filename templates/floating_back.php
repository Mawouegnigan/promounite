<a href="javascript:void(0);" class="floating-back hidden" id="floatingBack">
    ←
</a>

<script>
function goBack(){
    if(document.referrer !== ""){
        history.back();
    } else {
        window.location.href = "index.php";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("floatingBack");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 200) {
            btn.classList.add("show");
        } else {
            btn.classList.remove("show");
        }
    });

    btn.addEventListener("click", goBack);
});
</script>