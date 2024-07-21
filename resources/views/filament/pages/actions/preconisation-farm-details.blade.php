<div>
    @if(!is_null($farm))
        <div>
            @if(!is_null($farm->area))
            <p><strong>Superficie</strong> : {{ $farm->area }} {{ $farm->unit->name}}</p> @endif
            @if(!is_null($farm->density))
            <p><strong>Densité de la plantation</strong> : {{ $farm->density }} P/H</p> @endif
            @if(!is_null($farm->age))
            <p><strong>Age</strong> : {{ $farm->age }} ans</p> @endif
            @if(!is_null($farm->distance_tree))
            <p><strong>Distance Arbre</strong> : {{ $farm->distance_tree }} m</p> @endif
            @if(!is_null($farm->distance_line))
            <p><strong>Distance Ligne</strong> : {{ $farm->distance_line }} m</p> @endif
        </div>
    @endif

</div>
