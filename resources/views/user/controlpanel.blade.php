@extends('layouts.master')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body">
        <!-- Display Validation Errors -->

        <!-- New Task Form -->
        <form action="{{ url('task') }}" method="POST" class="form-horizontal">
            {!! csrf_field() !!}

            <!-- Task Name -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Task</label>

                <div class="col-sm-6">
                    <input type="text" name="name" id="task-name" class="form-control">
                </div>
				<div class="col-sm-6">
                    <textarea></textarea>
                </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit">
                        <i class="fa fa-plus"></i> Add Task
                    </button>
                </div>
            </div>
        </form>
    </div>
	
<h1>Lorem Ipsum</h1>
<p>Lorem ipsum dolor sit amet, <a href="#">consectetur adipiscing elit</a>. Vestibulum at volutpat mauris. Donec placerat sem gravida, finibus tellus a, malesuada nibh. Nam sagittis laoreet mauris sit amet imperdiet. Phasellus vel arcu felis. Mauris eu elit nec quam ornare sodales. Sed vel bibendum nibh, quis suscipit eros. Nam id tincidunt purus. Curabitur vestibulum lectus ut quam venenatis tristique. Curabitur elementum ante et finibus facilisis. Suspendisse congue quam a porta vulputate. Nunc libero nunc, lacinia et risus vel, consectetur consectetur ligula. Etiam sagittis nunc leo, ut feugiat purus finibus sit amet. Curabitur cursus tortor eget luctus dignissim.</p>

<p>Etiam vitae mi arcu. Maecenas dui orci, maximus in feugiat in, dapibus sed quam. Duis turpis ligula, faucibus eget est eu, blandit sodales augue. Pellentesque tristique massa ultricies sapien lobortis vehicula. Duis in mi non lectus posuere molestie vitae gravida libero. Proin fringilla nisl lorem, eu facilisis purus accumsan vitae. Aenean dui tellus, bibendum vel enim vel, ultricies lacinia augue. Suspendisse in tortor tincidunt, lobortis leo et, egestas libero. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nam ultrices, nisl sed laoreet maximus, felis eros blandit nisi, sit amet tristique neque metus vitae dui. Nulla gravida laoreet justo, sed rhoncus arcu posuere sed.</p>

<h2>Lorem Sub</h2>
<p>Quisque feugiat pellentesque odio, eu euismod leo porta fringilla. Etiam rhoncus, felis et rutrum vehicula, purus sem ultricies nulla, quis sagittis purus diam vel felis. Proin quis sollicitudin lorem. Nam congue id est nec porttitor. Phasellus mattis, orci eu ornare ullamcorper, tortor justo mattis dui, vel aliquet erat ipsum nec nunc. Integer dignissim sed tortor sit amet consequat. Curabitur eget massa vitae quam tristique consectetur.</p>

<p>Maecenas ut nisi pellentesque, sagittis erat a, elementum velit. Nam in nulla ut quam pharetra egestas. Donec blandit elementum cursus. Nulla accumsan in tellus at dictum. Donec a lorem tellus. Nam semper dictum justo ac ornare. Pellentesque viverra nisl dui, sit amet mollis elit dictum in. Aenean convallis vitae metus eget finibus. Donec pretium consectetur justo, vitae hendrerit nulla dapibus vel. Donec fermentum libero in orci blandit aliquet. Ut a consectetur lorem. Mauris sollicitudin ex vitae diam iaculis, eget sagittis lorem consectetur. Integer posuere diam ac risus lobortis, ac luctus massa ultricies. Maecenas non magna a est auctor condimentum varius sit amet nunc. Sed iaculis purus ac dolor eleifend, sed ornare dolor viverra. Morbi hendrerit eu turpis a lobortis.</p>

<p>Quisque vel dui ex. Quisque scelerisque, magna sed mattis condimentum, felis turpis pulvinar magna, ut pulvinar sem nulla sed metus. Nunc eu libero ac mi vulputate vestibulum. Nam iaculis pharetra dui a tincidunt. Morbi ullamcorper eu eros eget laoreet. Etiam a aliquet metus. Nulla convallis est dictum, porta libero et, ultrices nisi. Morbi pellentesque ligula venenatis, pretium ligula ac, congue arcu. Curabitur eu feugiat leo, sed tempus enim. Phasellus aliquet sollicitudin risus quis blandit. Morbi auctor leo nec eleifend porta. Aenean auctor massa sit amet est rutrum tincidunt. Phasellus posuere accumsan tellus, nec eleifend nulla efficitur ac. Vivamus elementum quis nulla ac ornare. Maecenas interdum, eros ac faucibus sollicitudin, turpis mauris venenatis erat, finibus hendrerit ante lacus non urna. Mauris auctor eros nibh, at vehicula lectus auctor ac.</p>

<h1>Lorem Ipsum</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum at volutpat mauris. Donec placerat sem gravida, finibus tellus a, malesuada nibh. Nam sagittis laoreet mauris sit amet imperdiet. Phasellus vel arcu felis. Mauris eu elit nec quam ornare sodales. Sed vel bibendum nibh, quis suscipit eros. Nam id tincidunt purus. Curabitur vestibulum lectus ut quam venenatis tristique. Curabitur elementum ante et finibus facilisis. Suspendisse congue quam a porta vulputate. Nunc libero nunc, lacinia et risus vel, consectetur consectetur ligula. Etiam sagittis nunc leo, ut feugiat purus finibus sit amet. Curabitur cursus tortor eget luctus dignissim.</p>

<p>Etiam vitae mi arcu. Maecenas dui orci, maximus in feugiat in, dapibus sed quam. Duis turpis ligula, faucibus eget est eu, blandit sodales augue. Pellentesque tristique massa ultricies sapien lobortis vehicula. Duis in mi non lectus posuere molestie vitae gravida libero. Proin fringilla nisl lorem, eu facilisis purus accumsan vitae. Aenean dui tellus, bibendum vel enim vel, ultricies lacinia augue. Suspendisse in tortor tincidunt, lobortis leo et, egestas libero. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nam ultrices, nisl sed laoreet maximus, felis eros blandit nisi, sit amet tristique neque metus vitae dui. Nulla gravida laoreet justo, sed rhoncus arcu posuere sed.</p>

<h3>Lorem Sub 3</h3>
<p>Quisque feugiat pellentesque odio, eu euismod leo porta fringilla. Etiam rhoncus, felis et rutrum vehicula, purus sem ultricies nulla, quis sagittis purus diam vel felis. Proin quis sollicitudin lorem. Nam congue id est nec porttitor. Phasellus mattis, orci eu ornare ullamcorper, tortor justo mattis dui, vel aliquet erat ipsum nec nunc. Integer dignissim sed tortor sit amet consequat. Curabitur eget massa vitae quam tristique consectetur.</p>

<p>Maecenas ut nisi pellentesque, sagittis erat a, elementum velit. Nam in nulla ut quam pharetra egestas. Donec blandit elementum cursus. Nulla accumsan in tellus at dictum. Donec a lorem tellus. Nam semper dictum justo ac ornare. Pellentesque viverra nisl dui, sit amet mollis elit dictum in. Aenean convallis vitae metus eget finibus. Donec pretium consectetur justo, vitae hendrerit nulla dapibus vel. Donec fermentum libero in orci blandit aliquet. Ut a consectetur lorem. Mauris sollicitudin ex vitae diam iaculis, eget sagittis lorem consectetur. Integer posuere diam ac risus lobortis, ac luctus massa ultricies. Maecenas non magna a est auctor condimentum varius sit amet nunc. Sed iaculis purus ac dolor eleifend, sed ornare dolor viverra. Morbi hendrerit eu turpis a lobortis.</p>

<p>Quisque vel dui ex. Quisque scelerisque, magna sed mattis condimentum, felis turpis pulvinar magna, ut pulvinar sem nulla sed metus. Nunc eu libero ac mi vulputate vestibulum. Nam iaculis pharetra dui a tincidunt. Morbi ullamcorper eu eros eget laoreet. Etiam a aliquet metus. Nulla convallis est dictum, porta libero et, ultrices nisi. Morbi pellentesque ligula venenatis, pretium ligula ac, congue arcu. Curabitur eu feugiat leo, sed tempus enim. Phasellus aliquet sollicitudin risus quis blandit. Morbi auctor leo nec eleifend porta. Aenean auctor massa sit amet est rutrum tincidunt. Phasellus posuere accumsan tellus, nec eleifend nulla efficitur ac. Vivamus elementum quis nulla ac ornare. Maecenas interdum, eros ac faucibus sollicitudin, turpis mauris venenatis erat, finibus hendrerit ante lacus non urna. Mauris auctor eros nibh, at vehicula lectus auctor ac.</p>
    <!-- TODO: Current Tasks -->
@endsection