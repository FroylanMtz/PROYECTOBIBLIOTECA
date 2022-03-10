<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{config('app.name')}}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 54px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Inicio</a>
                    @else
                        <a href="{{ route('login') }}">Iniciar sesión</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Registrarse</a>
                        @endif
                    @endauth
                </div>    
            @endif

            <div class="content">

                <div class="title m-b-md">
                    Sistema de gestion de biblioteca
                </div>

                <?xml version="1.0"?>
<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:svg="http://www.w3.org/2000/svg" id="svg2932" viewBox="0 0 64 64" version="1.1">
  <g id="layer1">
    <g id="g4014">
      <g id="g4010">
        <path id="path2944" fill="#303030" d="m9 16v43c0 3 3 5 5 5h35v-53h-35c-3 0-5 2-5 5z"/>
        <path id="path2946" fill="#ddd" d="m11 58c0 2 1 4 4 4h32v-8h-32c-3 0-4 2-4 4z"/>
      </g>
      <g id="g4004">
        <path id="path2948" fill="#505050" d="m18 54h31v-43h-31v43z"/>
        <path id="path3978" fill="#008a00" d="m14 6v43c0 3 3 5 5 5h35v-53h-35c-3 0-5 2-5 5z"/>
        <path id="path3980" fill="#ddd" d="m16 48c0 2 1 4 4 4h32v-8h-32c-3 0-4 2-4 4z"/>
        <path id="path3982" fill="#60a917" d="m23 44h31v-43h-31v43z"/>
      </g>
      <path id="path3989" fill="#7a3b3f" d="m9 43c0-3 4-13 10-13h35v-10h-35c-7 0-10 7-10 11v12z"/>
    </g>
  </g>
  <metadata>
    <rdf:RDF>
      <cc:Work>
        <dc:format>image/svg+xml</dc:format>
        <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"/>
        <cc:license rdf:resource="http://creativecommons.org/licenses/publicdomain/"/>
        <dc:publisher>
          <cc:Agent rdf:about="http://openclipart.org/">
            <dc:title>Openclipart</dc:title>
          </cc:Agent>
        </dc:publisher>
        <dc:title>books</dc:title>
        <dc:date>2013-10-12T15:15:12</dc:date>
        <dc:description>book pack</dc:description>
        <dc:source>http://openclipart.org/detail/184800/books-by-crisg-184800</dc:source>
        <dc:creator>
          <cc:Agent>
            <dc:title>crisg</dc:title>
          </cc:Agent>
        </dc:creator>
        <dc:subject>
          <rdf:Bag>
            <rdf:li>book</rdf:li>
            <rdf:li>book folder</rdf:li>
            <rdf:li>book library</rdf:li>
            <rdf:li>book pack</rdf:li>
            <rdf:li>clip art</rdf:li>
            <rdf:li>icon</rdf:li>
            <rdf:li>library</rdf:li>
            <rdf:li>libreria</rdf:li>
            <rdf:li>libri</rdf:li>
            <rdf:li>my books</rdf:li>
          </rdf:Bag>
        </dc:subject>
      </cc:Work>
      <cc:License rdf:about="http://creativecommons.org/licenses/publicdomain/">
        <cc:permits rdf:resource="http://creativecommons.org/ns#Reproduction"/>
        <cc:permits rdf:resource="http://creativecommons.org/ns#Distribution"/>
        <cc:permits rdf:resource="http://creativecommons.org/ns#DerivativeWorks"/>
      </cc:License>
    </rdf:RDF>
  </metadata>
</svg>

            </div>
        </div>
    </body>
</html>
