<div class="table">

    <table class="{{ $css->table }}">

        <thead>

            <tr class="mass-action" style="display: none; height:63px;">

                <th colspan="{{ count($columns)+1 }}">

                    <div class="mass-action-wrapper">

                        <span class="massaction-remove">
                            <span class="icon checkbox-dash-icon"></span>
                        </span>

                        @foreach($massoperations as $massoperation)

                            @if($massoperation['type'] == "button")

                            <form onsubmit="return confirm('Are You Sure?');"
                                @if(strtoupper($massoperation[ 'method'])=="GET" || strtoupper($massoperation['method'])=="POST" )
                                    method="{{ strtoupper($massoperation['method']) }}"

                                @else
                                    method="POST"
                                @endif

                                action="{{ $massoperation['route'] }}">

                                {{ csrf_field() }}

                                @if(strtoupper($massoperation['method'])!= "GET" && strtoupper($massoperation['method'])!= "POST")

                                @method($massoperation['method'])

                                @endif

                                <input type="hidden" id="indexes" name="indexes" value="">

                                <input class="btn btn-primary btn-sm" type="submit" value="Delete">

                            </form>

                            @elseif($massoperation['type'] == "select")

                                <form
                                    @if(strtoupper($massoperation[ 'method'])=="GET" || strtoupper($massoperation[ 'method'])=="POST" )

                                        method="{{ strtoupper($massoperation['method']) }}"

                                    @else
                                        method="POST"

                                        @endif

                                    action="{{ $massoperation['route'] }}">
                                        {{ csrf_field() }}
                                    @if(strtoupper($massoperation['method'])!= "GET" && strtoupper($massoperation['method'])!= "POST")

                                        @method($massoperation['method'])

                                    @endif

                                    <input type="hidden" id="indexes" name="indexes" value="">

                                    <select name="choices">

                                        @foreach($massoperation['options'] as $option)

                                            <option>{{ $option }}</option>

                                        @endforeach

                                    </select>

                                        <input class="btn btn-primary btn-sm" type="submit" value="Submit">

                                </form>
                            @endif
                        @endforeach
                    </div>
                </th>
            </tr>
            <tr class="table-grid-header">
                <th>
                    <span class="checkbox">
                        <input type="checkbox" id="mastercheckbox">
                        <label class="checkbox-view" for="checkbox"></label>
                    </span>
                </th>
                @foreach ($columns as $column)
                    @if($column->sortable == "true")
                        <th class="grid_head"
                            @if(strpos($column->alias, ' as '))
                                <?php $exploded_name = explode(' as ',$column->name); ?>
                                data-column-name="{{ $exploded_name[0] }}"
                            @else
                                data-column-name="{{ $column->alias }}"
                            @endif

                            data-column-label="{{ $column->label }}"
                                data-column-sort="asc">{!! $column->sorting() !!}<span class="icon sort-down-icon"></span>
                        </th>
                        @else
                            <th class="grid_head" data-column-name="{{ $column->alias }}" data-column-label="{{ $column->label }}">{!! $column->sorting() !!}</th>
                    @endif
                @endforeach
                <td>Name</td>
                {{-- @if(isset($attribute_columns))
                    @foreach($attribute_columns as $key => $value)
                        <th class="grid_head"
                            data-column-name="{{ $attributeAliases[$key] }}"
                            data-column-label="{{ $attributeAliases[$key] }}"
                            data-column-sort="asc"
                        >
                            {{ $value }}<span class="icon sort-down-icon"></span>
                        </th>
                    @endforeach
                @endif --}}
                <th>
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="{{ $css->tbody }}">
            @foreach ($results as $result)
            <tr>

                <td class="">
                    <span class="checkbox">
                        <input type="checkbox" class="indexers" id="{{ $result->id }}" name="checkbox[]">
                        <label class="checkbox-view" for="checkbox1"></label>
                    </span>
                </td>
                @foreach ($columns as $column)
                    <td class="">{!! $column->render($result) !!}</td>
                @endforeach
                {{-- @if(isset($attribute_columns))
                @foreach ($attribute_columns as $atc)
                    <td>{{ $result->{$atc} }}</td>
                @endforeach
                @endif --}}
                <td></td>

                <td class="action">
                    @foreach($actions as $action)
                        <a @if($action['type'] == "Edit") href="{{ url()->current().'/edit/'.$result->id }}" @elseif($action['type']=="Delete") href="{{ url()->current().'/delete/'.$result->id }}" @endif  class="Action-{{ $action['type'] }}" id="{{ $result->id }}" onclick="return confirm_click('{{ $action['confirm_text'] }}');">
                            <i class="{{ $action['icon'] }}"></i>
                        </a>
                    @endforeach
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $results->links() }}
    </div>
</div>