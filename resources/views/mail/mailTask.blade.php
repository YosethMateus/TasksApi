@component('mail::message')

Hola, <b>{{ $task->assignedTo->name }}</b>

Se te ha asignado una nueva tarea en el sistema.

@component('mail::panel')
<b>Título:</b> {{ $task->title }}  
<b>Descripción:</b> {{ $task->description }}  
<b>Fecha Límite:</b> {{ $task->due_date }}
@endcomponent

@component('mail::button', ['url' => url('/tasks/'.$task->id)])
Ver Tarea
@endcomponent

Gracias,  
<b>El equipo de gestión de tareas</b>
@endcomponent