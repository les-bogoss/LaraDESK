@extends('layouts.app')
@section('title', 'Tickets - ' . config('app.name', 'Laravel'))

@section('content')

    <div class="tickets-container">
        <div class="tickets-wrapper">
            @if (Auth::user()->hasPerm('create-ticket'))
                <div class="tickets-create">
                    <button id="createRoleButton">Create Ticket</button>
                </div>
            @endif
            <table class="tickets-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Ticket</th>
                        <th>Priority</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $t)
                        <tr onclick="window.location.href = '{{ route('tickets.show', ['ticket' => $t]) }}'"
                            class="{{ isset($ticket) && $t == $ticket ? 'active' : 'inactive' }}">
                            <td><img src="{{ $t->user()->first()->avatar }}"
                                    alt="{{ $t->user()->first()->first_name }} avatar"></td>
                            <td>{{ strtoupper($t->title) }}</td>
                            <td>{{ $t->priority }}</td>
                            <td>{{ $t->created_at->diffForHumans() }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                                <button class="ticket-info-header-button" id="editTicketButton">Edit</button>
                            @endif
                            @if (Auth::user()->hasPerm('delete-ticket'))
                                <button class="ticket-info-header-button" id="deleteTicketButton">Delete</button>
                            @endif
                        </div>
                        <div class="ticket-content">
                            <form action="{{ route('tickets.createContent', ['ticket' => $ticket]) }}" method="POST">
                                @csrf
                                <div class="ticket-content-card ticket-content-card-input">
                                    <input type="text" name="content" placeholder="Ecrivez qlq chose ..." >
                                </div>
                            </form>
                            @foreach ($ticket->ticket_content->reverse() as $tc)
                                <div class="ticket-content-card">
                                    <div class="ticket-content-card-user">
                                        <img src="{{ $tc->user()->first()->avatar }}"
                                            alt="{{ $tc->user()->first()->first_name }} avatar">
                                        <p>{{ $tc->user()->first()->first_name }} </p>
                                        <ul>
                                            @foreach ($tc->user()->first()->roles as $role)
                                                <li class="role">{{ $role->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <p>{{ $tc->text }}</p>
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
                                <select name="ticket_priority" class="{{ $statusColor[$ticket->status_id] }}"
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
                    </div>
                </div>
            </div>
        </div>
    @endisset
    </div>
    </div>

@endsection
