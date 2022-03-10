<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      
      <li class="nav-item">
        <router-link to="/dashboard" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt yellow"></i>
          <p>
            Inicio
          </p>
        </router-link>
      </li>
      
      <!-- MENU PARA LAS INSTITUCIONES -->
      @if(Auth::user()->type == 'admin')
      <li class="nav-item">
        <router-link to="/instituciones" class="nav-link">
          <i class="nav-icon fas fa-school blue"></i>
          <p>
            Escuelas
          </p>
        </router-link>
      </li>
      @endif

      <!-- MENU PARA LAS BIBLIOTECAS -->
      @if(Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin')
      <li class="nav-item">
        <router-link to="/bibliotecas" class="nav-link">
          <i class="nav-icon fas fa-book-reader purple"></i>
          <p>
            Bibliotecas
          </p>
        </router-link>
      </li>
      @endif

     <!-- MENU PARA LOS ALUMNOS -->
     @if(Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario')
     <li class="nav-item">
      <router-link to="/alumnos" class="nav-link">
        <i class="nav-icon fas fa-user-graduate pink"></i>
        <p>
          Alumnos
        </p>
      </router-link>
    </li>
      @endif

      <!-- MENU PARA LOS PROFESORES -->
      @if(Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario')
      <li class="nav-item">
        <router-link to="/profesores" class="nav-link">
          <i class="nav-icon fas fa-chalkboard-teacher orange"></i>
          <p>
            Profesores
          </p>
        </router-link>
      </li>
     @endif

      <!-- MENU PARA EL PERSONAL DE LAS BIBLIOTECAS -->
      @if(Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' )
      <li class="nav-item">
        <router-link to="/personal" class="nav-link">
          <i class="nav-icon fas fa-users green"></i>
          <p>
            Bibliotecarios
          </p>
        </router-link>
      </li>
      @endif

      <!-- MENU PARA LOS PRESTAMOS DE LIBROS -->
      @if(Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario' || Auth::user()->type == 'alumno' || Auth::user()->type == 'profesor' )
        <li class="nav-item">
          <router-link to="/prestamos" class="nav-link">
            <i class="nav-icon fab fa-leanpub yellow"></i>
            <p>
              Prestamos
            </p>
          </router-link>
        </li>
      @endif

      <!-- MENU PARA LOS LIBROS -->
      @if(Auth::user()->type == 'profesor' || Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario' || Auth::user()->type == 'alumno' )
      <li class="nav-item">
        <router-link to="/libros" class="nav-link">
          <i class="nav-icon fas fa-book teal"></i>
          <p>
            Libros
          </p>
        </router-link>
      </li>
      @endif

      <!-- MENU PARA LAS COPIAS -->
      @if( Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario')
      <li class="nav-item">
        <router-link to="/copias" class="nav-link">
          <i class="nav-icon fas fa-copy cyan"></i>
          <p>
            Copias
          </p>
        </router-link>
      </li>
      @endif

      <!-- MENU PARA LAS EDITORIALES -->
      @if( Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario')
      <li class="nav-item">
        <router-link to="/autores" class="nav-link">
          <i class="nav-icon fas fa-user indigo"></i>
          <p>
            Autores
          </p>
        </router-link>
      </li>
      @endif


      <!-- MENU PARA LAS EDITORIALES -->
      @if( Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario')
      <li class="nav-item">
        <router-link to="/editoriales" class="nav-link">
          <i class="nav-icon fas fa-newspaper blue"></i>

          <p>
            Editoriales
          </p>
        </router-link>
      </li>
      @endif




      <!-- OPCION PARA CERRAR LA SESIÓN -->
      <li class="nav-item">
        <a href="#" class="nav-link" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="nav-icon fas fa-power-off red"></i>
          <p>
            {{ __('Cerrar Sesión') }}
          </p>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </nav>