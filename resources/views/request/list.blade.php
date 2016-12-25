@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Requests</div>

                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead><tr>
                                <td>#</td>
                                <td>Title</td>
                                <td>Token</td>
                                <td>Type</td>
                                <td>Responded?</td>
                                <td>Exported?</td>
                                <td>Actions</td>
                            </tr></thead>
                            <tbody>
                                @foreach ($requests as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->title }}<br>
                                        <td><a href="{{ url("/request/{$request->token}") }}" target="_blank">{{ $request->token }}</a></td>
                                        <td>{{ $request->address_type }}</td>
                                        <td>{{ $request->exported ? 'yes' : 'no' }}</td>
                                        <td>{{ $request->responded ? 'yes' : 'no' }}</td>
                                        <td>
                                            <a href="{{ url("/request/{$request->token}/detail") }}" class="btn btn-primary" target="_blank">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $requests->links() }}
                    </div> <!-- /.panel-body -->
                </div> <!-- /.panel -->
            </div> <!-- /.col-sm-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
@endsection
