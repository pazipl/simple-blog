(function(global){

    var trashLinks;
    var trashFn = function (e) {

        var xhr;
        var confirm;

        e.preventDefault();
        e.stopPropagation();

        confirm = global.confirm('Czy napewno chcesz usunąć ten wpis?');

        if (confirm) {
            xhr = new XMLHttpRequest();
            xhr.open('GET', this.getAttribute('href'));
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    global.location.reload(true);
                }
            };

            xhr.send();
        }


        return false;
    };

    trashLinks = document.querySelectorAll('a.trash-link') || [];

    for (var i = 0, total = trashLinks.length; i < total; i++ ) {
        (function (i){
            trashLinks[i].addEventListener('click', trashFn, false);
        }(i))
    }






}(window));
