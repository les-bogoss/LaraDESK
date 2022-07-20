@extends('layouts.app')
@isset($ticket)
    @section('title', 'Ticket (' . $ticket->id . ') - ' . config('app.name', 'Laravel'))
@else
@section('title', 'Tickets - ' . config('app.name', 'Laravel'))
@endisset

@section('content')
<div class="tickets-container">
    <div class="tickets-wrapper">
        @if (Auth::user()->hasPerm('create-ticket'))
            <div class="tickets-create">
                <div class="create-bg">
                    <button name="createRoleButton">+ Create Ticket</button>
                    <div id="search"><input id="search-bar" onkeyup="searchTickets()" /><i class="fa fa-search"></i>
                    </div>
                    <script>
                        function searchTickets() {
                            // Declare variables
                            var input, filter, ul, li, a, i, txtValue;
                            input = document.getElementById('search-bar');
                            filter = input.value.toUpperCase();
                            ul = document.getElementById("ticket-list");
                            li = ul.getElementsByClassName('ticket-item');

                            // Loop through all list items, and hide those who don't match the search query
                            for (i = 0; i < li.length; i++) {
                                a = li[i].getElementsByTagName("div")[0].getElementsByTagName("div")[0].getElementsByTagName("h1")[0];
                                txtValue = a.textContent || a.innerText;
                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                    li[i].style.display = "";
                                } else {
                                    li[i].style.display = "none";
                                }
                            }
                        }
                    </script>
                </div>
            </div>
            <div id="createRole" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="close">&times;</span>
                        <h2>Create ticket :</h2>
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
        @endif
        <div class="ticket-list-wrapper">
            <div class="tickets" id="ticket-list">
                @foreach ($tickets->sortByDesc('created_at') as $t)
                    <div onclick="window.location.href = '{{ route('tickets.show', ['ticket' => $t]) }}'"
                        class="{{ isset($ticket) && $t == $ticket ? 'active ticket-item' : 'inactive ticket-item' }}"
                        id="ticket-{{ $t->id }}" title="{{ $t->title }}">
                        <div class="ticket-details">
                            <img src="{{ $t->user()->first()->avatar }}"
                                alt="{{ $t->user()->first()->first_name }} avatar">
                            <div>
                                <h1 class="title">
                                    {{ strlen($t->title) >= 25 ? substr($t->title, 0, 23) . '...' : $t->title }}
                                </h1>
                                <p class="time">{{ $t->created_at->diffForHumans() }}</p>
                            </div>
                            <h3 class="priority">
                                <i class="fa-solid fa-circle-exclamation"
                                    style="{{ $t->priority == 1 ? 'color : green;' : ($t->priority == 2 ? 'color: orange;' : 'color: red;') }}"></i>
                            </h3>
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
                    <h1 class="ticket-id">
                        <i class="ticket-logo fa-solid fa-ticket-simple"></i>{{ $ticket->id }}
                    </h1>
                    <h1 class="ticket-title">{{ $ticket->title }}</h1>
                    <div class="ticket-info-header-buttons">
                        @if (Auth::user()->hasPerm('delete-ticket'))
                            <button name="warningButton" class="ticket-info-header-button" data-msg="to delete this ticket"
                                data-method="DELETE"
                                data-route="{{ route('tickets.destroy', ['ticket' => $ticket]) }}">DELETE</button>
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
                                            @if ($ticket->assignedUser()->first())
                                                @if ($ticket->assignedUser()->first()->first_name . ' ' . $ticket->assignedUser()->first()->last_name ==
                                                    $tc->user()->first()->first_name . ' ' . $tc->user()->first()->last_name)
                                                    <li class="role" style="background-color: #ffc107;">
                                                        Assigned</li>
                                                @endif
                                            @endif
                                        </ul>

                                        <p class="time">{{ $tc->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if (Auth::user()->hasPerm('delete-ticket') || Auth::user()->id == $tc->user_id)
                                        <button name="warningButton" data-msg="to delete this message" data-method="DELETE"
                                            data-route="{{ route('tickets.deleteContent', ['ticket' => $ticket, 'content' => $tc]) }}"><i
                                                class="fa-solid fa-trash-can"></i></button>
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
                            <x-select color="secondary" name="ticket_status"
                                class="{{ $statusColor[$ticket->status_id] }}" id="ticket-status-select"
                                onchange="this.form.submit();">
                                <option value="OUVERT" {{ $ticket->status_id == '1' ? 'selected' : '' }}>OUVERT</option>
                                <option value="ATTRIBUÉ" {{ $ticket->status_id == '2' ? 'selected' : '' }}>ATTRIBUÉ
                                </option>
                                <option value="EN ATTENTE" {{ $ticket->status_id == '3' ? 'selected' : '' }}>ATTENTE
                                    RÉPONSE</option>
                                <option value="CLOS" {{ $ticket->status_id == '4' ? 'selected' : '' }}>CLOS</option>
                                <option value="RÉSOLU" {{ $ticket->status_id == '5' ? 'selected' : '' }}>RÉSOLU</option>
                            </x-select>
                        </form>
                    </div>
                    <div class="ticket-info-section">
                        <h2>Priority</h2>
                        <form action="{{ route('tickets.editPriority', ['ticket' => $ticket]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <x-select color="primary" name="ticket_priority"
                                class="ticket-priority {{ $priorityColor[$ticket->priority] }}"
                                id="ticket-priority-select" onchange="this.form.submit();">
                                <option value="1" {{ $ticket->priority == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ $ticket->priority == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ $ticket->priority == '3' ? 'selected' : '' }}>3</option>
                            </x-select>
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
                            </p>
                        @endisset
                        <form action="{{ route('tickets.editTechnician', ['ticket' => $ticket]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <x-select color="secondary" name="technician" class="technician"
                                onchange="this.form.submit();">
                                @if (!isset($ticket->assignedUser()->first()->first_name))
                                    <option value="">No assignee</option>
                                @endif
                                @foreach ($technicians as $technician)
                                    <option value="{{ $technician->id }}"
                                        {{ isset($ticket->assignedUser->first()->id) ? ($ticket->assignedUser->first()->id == $technician->id ? 'selected' : '') : '' }}>
                                        {{ $technician->first_name . ' ' . $technician->last_name }}</option>
                                @endforeach
                            </x-select>
                        </form>
                    </div>
                    <div class="ticket-info-section">
                        <h2>User</h2>
                        <div class="user-info">
                            <img class="avatar" src="{{ $ticket->user()->first()->avatar }}">
                            <p>{{ $ticket->user()->first()->first_name . ' ' . $ticket->user()->first()->last_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endisset
</div>
</div>
@isset($ticket)
    <div id="warning" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Warning :</h2>
            </div>
            <form action="" method="post" id="warning_form">
                @csrf
                @method('')
                <div class="modal-body">
                    <p>Are you sure you want <span id="warning_message"></span> ?</p>
                </div>
                <div class="modal-footer warning-footer">
                    <button type="submit">Yes</button>
                    <button type="button" class="close">No</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        var modal = document.querySelectorAll('.modal')
        modal.forEach(element => {
            var span = element.querySelectorAll('.close');
            var open_button = document.getElementsByName(element.id + "Button")

            open_button.forEach(button => {
                button.addEventListener('click', () => {
                    element.style.display = "flex";
                    if (element.id == "warning") {
                        element.querySelectorAll("#warning_message").forEach(element => {
                            element.innerHTML = button.dataset.msg;
                            document.getElementById("warning_form").action = button.dataset
                                .route;
                            document.getElementById("warning_form").querySelector(
                                    "[name=_method]")
                                .value = button.dataset.method;
                        });
                    }
                });

                span.forEach(bt => {
                    bt.addEventListener('click', () => {
                        element.style.display = "none";
                    });
                });


                if (element.id == "createRole") {
                    @error('*')
                        modal.style.display = "flex";
                    @enderror
                }
            })
        })
        window.onclick = function(event) {
            modal.forEach(element => {
                if (event.target == element) {
                    element.style.display = "none";
                }
            });
        }

        var ticket = document.getElementById('ticket-{{ $ticket->id }}');
        document.getElementsByClassName('tickets-wrapper')[0].scrollTo(0, ticket.offsetTop - 185);

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

        const ticketList = document.querySelector('.tickets-wrapper');

        function resizeMobileTicketList() {
            if (window.matchMedia("(max-width: 1024px)").matches) {
                ticketList.style.display = 'none';
            } else {
                ticketList.style.display = 'block';
            }
        }

        window.onresize = resizeMobileTicketList;
        resizeMobileTicketList();
    </script>

    <button class="return">
        <a href="{{ route('tickets.index') }}">
            <i class="fas fa-arrow-left"></i>
            <span>Retour</span>
        </a>
    </button>
@else
    <script>
        const ticket = document.querySelector('.ticket');

        function resizeMobileTicket() {
            if (window.matchMedia("(max-width: 1024px)").matches) {
                ticket.style.display = 'none';
            } else {
                ticket.style.display = 'block';
            }
        }

        window.onresize = resizeMobileTicket;
        resizeMobileTicket();
    </script>
@endisset
@endsection
