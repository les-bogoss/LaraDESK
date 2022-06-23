@extends('layouts.app')
@isset($ticket)
    @section('title', 'Ticket ' . $ticket->id . ' - ' . config('app.name', 'Laravel'))
@else
@section('title', 'Tickets - ' . config('app.name', 'Laravel'))
@endisset

@section('content')


<div class="tickets-container">
    <div class="tickets-wrapper">
        @if (Auth::user()->hasPerm('create-ticket'))
            <div class="tickets-create">
                <div class="create-bg">
                    <button id="createRoleButton">Create Ticket</button>
                </div>
            </div>
            <div id="createRole" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="close">&times;</span>
                        <h2>Create Role :</h2>
                    </div>
                    <form action="{{ route('tickets.store') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="title">
                                <label for="title">Title :</label>
                                <input type="text" class="@error('title') error @enderror" name="title"
                                    id="title" placeholder="Title" value="{{ old('title') }}">
                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="priority">
                                <label for="priority">Priority :</label>
                                <select name="priority" class="ticket-priority " id="ticket-priority-select">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">CREATE</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                var modal = document.getElementById("createRole");

                // Get the button that opens the modal
                var btn = document.getElementById("createRoleButton");

                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("close")[0];

                // When the user clicks on the button, open the modal
                btn.onclick = function() {
                    modal.style.display = "flex";
                }
                @error('*')
                    modal.style.display = "flex";
                @enderror
                // When the user clicks on <span> (x), close the modal
                span.onclick = function() {
                    modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }
            </script>
        @endif
        <div class="ticket-list-wrapper">
            <div class="tickets">
                @foreach ($tickets->sortByDesc('created_at') as $t)
                    <div onclick="window.location.href = '{{ route('tickets.show', ['ticket' => $t]) }}'"
                        class="{{ isset($ticket) && $t == $ticket ? 'active ticket-item' : 'inactive ticket-item' }}"
                        id="ticket-{{ $t->id }}">
                        <div class="ticket-details">
                            <img src="{{ $t->user()->first()->avatar }}"
                                alt="{{ $t->user()->first()->first_name }} avatar">
                            <div>
                                <h1 class="title" title="{{strtoupper($t->title)}}">
                                    {{ strlen(strtoupper($t->title)) > 25 ? substr(strtoupper($t->title), 0, (strlen(strtoupper($t->title))-28)) . '...' : strtoupper($t->title) }}
                                </h1>
                                <p class="time">{{ $t->created_at->diffForHumans() }}</p>
                            </div>
                            <h3 class="priority">{{ $t->priority }}</h3>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="ticket">
        @isset($ticket)
            <div class="ticket-info">
                <div class="ticket-info-header">
                    <h1 class="ticket-id"><i
                            class="ticket-logo fa-solid fa-ticket-simple"></i>{{ strtoupper($ticket->id) }}</h1>
                    <h1 class="ticket-title">{{ strtoupper($ticket->title) }}</h1>
                    <div class="ticket-info-header-buttons">
                        @if (Auth::user()->hasPerm('update-ticket'))
                            <button class="ticket-info-header-button" id="editTicketButton">EDIT</button>
                        @endif
                        @if (Auth::user()->hasPerm('delete-ticket'))
                            <form action="{{ route('tickets.destroy', ['ticket' => $ticket]) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="ticket-info-header-button" type="submit">DELETE</button>
                            </form>
                        @endif
                    </div>
                    <div class="ticket-content">
                        <form action="{{ route('tickets.createContent', ['ticket' => $ticket]) }}" method="POST">
                            @csrf
                            <textarea placeholder="{{ $ticket->status_id >= 4 ? 'ticket clos' : 'Ecrivez qlq chose ...' }}" type="text"
                                name="content" @if ($ticket->status_id >= 4) readonly @endif id="input-content"></textarea>
                            <button id="submit_content_button" type="submit" class="submit-message"
                                @if ($ticket->status_id >= 4) disabled @endif>submit</button>
                        </form>
                        @foreach ($ticket->ticket_content->reverse() as $tc)
                            <div class="ticket-content-card">
                                <div class="ticket-content-card-header">
                                    <div class="ticket-content-card-user">
                                        <img src="{{ $tc->user()->first()->avatar }}"
                                            alt="{{ $tc->user()->first()->first_name }} avatar">
                                        <p class="name">{{ $tc->user()->first()->first_name }} </p>

                                        <ul>
                                            @foreach ($tc->user()->first()->roles as $role)
                                                <li class="role" style="background-color: {{ $role->color }}">
                                                    {{ $role->name }}</li>
                                            @endforeach
                                        </ul>

                                        <p class="time">{{ $tc->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if (Auth::user()->hasPerm('delete-ticket') || Auth::user()->id == $tc->user_id)
                                        <form
                                            action="{{ route('tickets.deleteContent', ['ticket' => $ticket, 'content' => $tc]) }}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    @endif
                                </div>
                                <p>{!! $tc->text !!}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="ticket-info-body">
                    <div class="ticket-info-section">
                        <h2>Date</h2>
                        <p>{{ $ticket->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="ticket-info-section">
                        <h2>Status</h2>
                        <form action="{{ route('tickets.editStatus', ['ticket' => $ticket]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="ticket_status" class="{{ $statusColor[$ticket->status_id] }}"
                                id="ticket-status-select" onchange="this.form.submit();">
                                <option value="OUVERT" {{ $ticket->status_id == '1' ? 'selected' : '' }}>OUVERT</option>
                                <option value="ATTRIBUÉ" {{ $ticket->status_id == '2' ? 'selected' : '' }}>ATTRIBUÉ
                                </option>
                                <option value="EN ATTENTE" {{ $ticket->status_id == '3' ? 'selected' : '' }}>ATTENTE
                                    RÉPONSE</option>
                                <option value="CLOS" {{ $ticket->status_id == '4' ? 'selected' : '' }}>CLOS</option>
                                <option value="RÉSOLU" {{ $ticket->status_id == '5' ? 'selected' : '' }}>RÉSOLU</option>
                            </select>
                        </form>
                    </div>
                    <div class="ticket-info-section">
                        <h2>Priority</h2>
                        <form action="{{ route('tickets.editPriority', ['ticket' => $ticket]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="ticket_priority"
                                class="ticket-priority {{ $priorityColor[$ticket->priority] }}"
                                id="ticket-priority-select" onchange="this.form.submit();">
                                <option value="1" {{ $ticket->priority == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ $ticket->priority == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ $ticket->priority == '3' ? 'selected' : '' }}>3</option>
                            </select>
                        </form>
                    </div>
                    <div class="ticket-info-section">
                        <h2>Category</h2>
                        <p>{{ $ticket->ticket_category()->first()->name }}</p>
                    </div>
                    <div class="ticket-info-section">
                        <h2>Rating</h2>
                        <form action="{{ route('tickets.editRating', ['ticket' => $ticket]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="rating"
                                class="rating @isset($ticket->rating) {{ $priorityColor[$ticket->rating] }} @endisset"
                                id="ticket-rating-select" onchange="this.form.submit();">
                                <option value="1" {{ $ticket->rating == '1' ? 'selected' : '' }}>1 ⭐️</option>
                                <option value="2" {{ $ticket->rating == '2' ? 'selected' : '' }}>2 ⭐️</option>
                                <option value="3" {{ $ticket->rating == '3' ? 'selected' : '' }}>3 ⭐️</option>

                            </select>
                        </form>
                    </div>
                    <div class="ticket-info-section">
                        <h2>Assignee</h2>
                        @isset($ticket->assignedUser()->first()->first_name)
                            <img class="avatar" src="{{ $ticket->assignedUser()->first()->avatar }}">
                            <p>{{ $ticket->assignedUser()->first()->first_name . ' ' . $ticket->assignedUser()->first()->last_name }}
                            </p>
                        @else
                            <p>Aucun</p>
                        @endisset
                    </div>
                    <div class="ticket-info-section">
                        <h2>User</h2>
                        <img class="avatar" src="{{ $ticket->user()->first()->avatar }}">
                        <p>{{ $ticket->user()->first()->first_name . ' ' . $ticket->user()->first()->last_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endisset
</div>
</div>
@isset($ticket)
    <script>
        var ticket = document.getElementById('ticket-{{ $ticket->id }}');
        document.getElementsByClassName('tickets-wrapper')[0].scrollTo(0, ticket.offsetTop - 25);

        var textarea = document.getElementById("input-content");

        function submitOnEnter(event) {
            if (event.which === 13 && !event.shiftKey) {
                event.target.form.submit();
                event
                    .preventDefault(); // Prevents the addition of a new line in the text field (not needed in a lot of cases)
            }
        }
        document.getElementById("submit_content_button").onclick = function() {
            if (textarea.value.length > 0) {
                document.getElementById("submit_content_button").disabled = "true";
                document.getElementById("submit_content_button").innerHTML = "En cours...";
                document.getElementById("submit_content_button").parentNode.submit();
            } else {
                event.preventDefault();
            }
        };
        textarea.addEventListener("keypress", submitOnEnter);
        @if ($ticket->status_id < 4)
            textarea.focus();
        @endif
    </script>
@endisset
@endsection
