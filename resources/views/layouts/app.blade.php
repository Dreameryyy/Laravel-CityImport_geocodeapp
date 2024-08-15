<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Include Bootstrap and custom CSS -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container d-flex justify-content-between align-items-start">
                <div class="d-flex flex-column align-items-start">
                    <a class="navbar-brand mb-2" href="#">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:150px;">
                    </a>
                    <ul class="navbar-nav d-flex flex-row">
                        <li class="nav-item"><a class="nav-link" href="#">O nás</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Zoznam miest</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Inšpekcia</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Kontakt</a></li>
                    </ul>
                </div>
                <div class="ml-auto d-flex align-items-center right-bar">
                    <a class="nav-link" style="color: #1e9ae0;" href="#">Kontakty a čísla na oddelenia</a>
                    <a href="#" class="btn btn-light ml-2" style="color: #666;">EN ▼</a>
                    <form class="search-form form-inline my-2 my-lg-0 mr-3">
                        <div class="input-group">
                            <input class="form-control search-input" type="" placeholder="" aria-label="">
                            <div class="input-group-append">
                                <span class="input-group-text search-icon"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
                    </form>
                    <a class="btn btn-success ml-3" href="#">Prihlásenie</a>
                </div>

        </nav>
    </header>



    <main>
        @yield('content')
    </main>

    <footer class="bg-light ">
        <div class="container">
            <div class="row text-left py-4">
                <div class="col-md-3">
                    <h5>ADRESA A KONTAKT</h5>
                    <p>ŠÚKL<br>Kvetná 11<br>825 08 Bratislava 26<br>Ústredňa: +421-2-50701 111</p>
                    <h5 class="mt-4">KONTAKTY</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">telefónne čísla</a></li>
                        <li><a href="#">adresa</a></li>
                        <li><a href="#">úradné hodiny</a></li>
                        <li><a href="#">bankové spojenie</a></li>
                    </ul>
                    <h5 class="mt-4">INFORMÁCIE PRE VEREJNOSŤ</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Zoznam vyvezených liekov</a></li>
                        <li><a href="#">MZ SR</a></li>
                        <li><a href="#">Národný portál zdravia</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>O NÁS</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Dotazníky</a></li>
                        <li><a href="#">Hlavní predstavitelia</a></li>
                        <li><a href="#">Základné dokumenty</a></li>
                        <li><a href="#">Zmluvy za ŠÚKL</a></li>
                        <li><a href="#">História a súčasnosť</a></li>
                        <li><a href="#">Národná spolupráca</a></li>
                        <li><a href="#">Medzinárodná spolupráca</a></li>
                        <li><a href="#">Poradné orgány</a></li>
                        <li><a href="#">Legislatíva</a></li>
                        <li><a href="#">Prestupry a iné správne delikty</a></li>
                        <li><a href="#">Zoznam dlžníkov</a></li>
                        <li><a href="#">Sadzobník ŠÚKL</a></li>
                        <li><a href="#">Verejné obstarávanie</a></li>
                        <li><a href="#">Vzdelávacie akcie a prezentácie</a></li>
                        <li><a href="#">Konzultácie</a></li>
                        <li><a href="#">Voľné pracovné miesta</a></li>
                        <li><a href="#">Poskytovanie informácií</a></li>
                        <li><a href="#">Sťažnosti a petície</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>MÉDIÁ</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Tlačové správy</a></li>
                        <li><a href="#">Lieky v médiách</a></li>
                        <li><a href="#">Kontakt pre médiá</a></li>
                    </ul>
                    <h5 class="mt-4">DATABÁZY A SERVIS</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Databáza liekov a zdravotníckych pomôcok</a></li>
                        <li><a href="#">Iné zoznamy</a></li>
                        <li><a href="#">Kontaktný formulár</a></li>
                        <li><a href="#">Mapa stránok</a></li>
                        <li><a href="#">A - Z index</a></li>
                        <li><a href="#">Linky</a></li>
                        <li><a href="#">RSS</a></li>
                        <li><a href="#">Doplnok pre internetový prehliadač</a></li>
                        <li><a href="#">Prehliadače formátov</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>DROGOVÉ PREKURZORY</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Aktuality</a></li>
                        <li><a href="#">Legislatíva</a></li>
                        <li><a href="#">Pokyny</a></li>
                        <li><a href="#">Kontakt</a></li>
                    </ul>
                    <h5 class="mt-4">INÉ</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Linky</a></li>
                        <li><a href="#">Mapa stránok</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Podmienky používania</a></li>
                    </ul>
                    <h5 class="mt-4"  style="color: #007bff;">RAPID ALERT SYSTEM</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" style="color: #007bff; text-decoration: underline;">Rýchla výstraha vyplývajúca z nedostatkov v kvalite liekov</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>