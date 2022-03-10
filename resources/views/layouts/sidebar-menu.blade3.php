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

      <!-- <li class="nav-item">
        <router-link to="/products" class="nav-link">
          <i class="nav-icon fas fa-book green"></i>
          <p>
            Libros
          </p>
        </router-link>
      </li> -->

      @if(Auth::user()->type == 'admin')
        <!-- <li class="nav-item">
          <router-link to="/users" class="nav-link">
            <i class="fa fa-users nav-icon blue"></i>
            <p>Usuarios</p>
          </router-link>
        </li>-->
      @endif

      
      <!-- MENU PARA LAS INSTITUCIONES -->
      @if(Auth::user()->type == 'admin')
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-school"></i>
          <p>
            Instituciones
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">

          <li class="nav-item">
            <router-link to="/instituciones" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              <p>
                Lista de instituciones
              </p>
            </router-link>
          </li>

          <!-- <li class="nav-item">
            <router-link to="/bibliotecas/confirmar" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Confirmar registro
              </p>
            </router-link>
          </li> -->

          <!-- <li class="nav-item">
            <router-link to="/instituciones/confirmar" class="nav-link">
              <i class="nav-icon fas fa-check"></i>
              <p>
                Confirmar registro
              </p>
            </router-link>
          </li> -->
        </ul>
      </li>
      @endif


       <!-- MENU PARA LAS BIBLIOTECAS -->
      @if(Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin')
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-book-reader"></i>
          <p>
            Bibliotecas
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">

          <li class="nav-item">
            <router-link to="/bibliotecas" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              <p>
                Lista de bibliotecas
              </p>
            </router-link>
          </li>

          <!--
            <li class="nav-item">
            <router-link to="/bibliotecas/confirmar" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Confirmar registro
              </p>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/bibliotecas/registro" class="nav-link">
              <i class="nav-icon fas fa-check-square"></i>
              <p>
                Registro de biblioteca
              </p>
            </router-link>
          </li>
        -->

        </ul>
      </li>
      @endif



     <!-- MENU PARA LOS ALUMNOS -->
     @if(Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario')
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-user-graduate"></i>
          <p>
            Alumnos
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">

          <li class="nav-item">
            <router-link to="/alumnos" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              
              <p>
                Lista de alumnos
              </p>
            </router-link>
          </li>
        </ul>
      </li>
      @endif


      <!-- MENU PARA LOS PROFESORES -->
      @if(Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario')
     <li class="nav-item has-treeview">
       <a href="#" class="nav-link">
         <i class="nav-icon fas fa-chalkboard-teacher"></i>
         <p>
           Profesores
           <i class="right fas fa-angle-left"></i>
         </p>
       </a>
       <ul class="nav nav-treeview">
         <li class="nav-item">
           <router-link to="/profesores" class="nav-link">
             <i class="nav-icon fas fa-list-ol green"></i>
             <p>
               Lista de profesores
             </p>
           </router-link>
         </li>
       </ul>
     </li>
     @endif


      <!-- MENU PARA EL PERSONAL DE LAS BIBLIOTECAS -->
      @if(Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' )
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-users"></i>
          <p>
            Bibliotecarios
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <router-link to="/personal" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              <p>
                Lista de personal
              </p>
            </router-link>
          </li>
        </ul>
      </li>
      @endif




      <!-- MENU PARA LOS PRESTAMOS DE LIBROS -->
      @if(Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario' || Auth::user()->type == 'alumno' || Auth::user()->type == 'profesor' )
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fab fa-leanpub"></i>
            <p>
              Prestamos
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <router-link to="/prestamos" class="nav-link">
                <i class="nav-icon fas fa-list-ol green"></i>
                <p>
                  Lista de prestamos
                </p>
              </router-link>
            </li>

          </ul>
        </li>

      @endif


      



      


      <!-- MENU PARA LOS LIBROS -->
      @if(Auth::user()->type == 'profesor' || Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario' || Auth::user()->type == 'alumno' )
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-book"></i>
          <p>
            Libros
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">

          <li class="nav-item">
            <router-link to="/libros" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              <p>
                Lista de libros
              </p>
            </router-link>
          </li>


          <li class="nav-item">
            <router-link to="/libros/confirmar" class="nav-link">
              <i class="nav-icon fas fas fa-users"></i>
              <p>
                Autores
              </p>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/libros/confirmar" class="nav-link">
              <i class="nav-icon fas fas fa-building"></i>
              <p>
                Editoriales
              </p>
            </router-link>
          </li>

          <!--<li class="nav-item">
            <router-link to="/libros/registrar" class="nav-link">
            <i class="nav-icon fas fa-edit"></i>
              <p>
                Registrar libro
              </p>
            </router-link>
          </li>-->

          <!--<li class="nav-item">
            <router-link to="/libros/confirmar" class="nav-link">
              <i class="nav-icon fas fa-check-square"></i>
              <p>
                Confirmar registros
              </p>
            </router-link>
          </li>-->
        </ul>
      </li>
      @endif



      <!-- MENU PARA LOS LIBROS -->
      @if( Auth::user()->type == 'adminInst' || Auth::user()->type == 'admin' || Auth::user()->type == 'bibliotecario')
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-book"></i>
          <p>
            Copias
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">

          <li class="nav-item">
            <router-link to="/copias" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              
              <p>
                Lista de copias
              </p>
            </router-link>
          </li>
          
          <!--<li class="nav-item">
            <router-link to="/copias/registrar" class="nav-link">

              <i class="nav-icon fas fa-edit"></i>
              <p>
                Registrar copia
              </p>
            </router-link>
          </li>-->


          <!--<li class="nav-item">
            <router-link to="/copias/confirmar" class="nav-link">
              <i class="nav-icon fas fa-check-square"></i>
              <p>
                Confirmar registros de copias
              </p>
            </router-link>
          </li> -->
        </ul>
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