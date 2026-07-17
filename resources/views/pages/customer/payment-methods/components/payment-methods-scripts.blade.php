<script>

function toggleMenu(index) {

const menu = document.getElementById('menu-' + index);

document.querySelectorAll('[id^="menu-"]').forEach(function (el) {
if (el !== menu) {
el.classList.add('hidden');
}
});

menu.classList.toggle('hidden');

}

document.addEventListener('click', function (e) {

if (!e.target.closest('[id^="menu-"]') && !e.target.closest('button[onclick^="toggleMenu"]')) {

document.querySelectorAll('[id^="menu-"]').forEach(function (el) {
el.classList.add('hidden');
});

}

});

</script>
