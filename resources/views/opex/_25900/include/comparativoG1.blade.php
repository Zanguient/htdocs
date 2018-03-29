
<div class="bsc-container obj_resizable">
    <div class="tab-comparativo">
        
        <svg id="chart1" class="chart1"></svg>
        
    </div>
</div>

<script src="{{ asset('assets/js/nvd3.js') }}"></script>
    
    <script>
     nv.addGraph(function() {
        var chart = nv.models.cumulativeLineChart()
            .useInteractiveGuideline(true)
            .x(function(d) { return d[0] })
            .y(function(d) { return d[1]/100 })
            .color(d3.scale.category20().range())
            .average(function(d) { return d.mean/100; })
            .duration(300)
            .clipVoronoi(false);
        chart.dispatch.on('renderEnd', function() {
            console.log('render complete: cumulative line with guide line');
        });

        chart.xAxis.tickFormat(function(d) {
            return d3.time.format('%m/%d/%y')(new Date(d))
        });

        chart.yAxis.tickFormat(d3.format(',.1%'));

        d3.select('#chart1')
            .datum(cumulativeTestData())
            .call(chart);

        //TODO: Figure out a good way to do this automatically
        nv.utils.windowResize(chart.update);

        chart.dispatch.on('stateChange', function(e) { nv.log('New State:', JSON.stringify(e)); });
        chart.state.dispatch.on('change', function(state){
            nv.log('state', JSON.stringify(state));
        });

        return chart;
    });

    
    
    @php $cont = 0;
    @php $cont2 = 0;
    
    function cumulativeTestData() {    
    var histcatexplong = [
        @foreach ($graficos as $grafico)
            @php $cont++;
                @if ($cont === 1)
                    @php echo '{'
                @else
                    @php echo ',{'
                @endif
            
                @php echo '"key" : "'.$grafico[0].'" ,'
                @php echo '"values" : ['
                    @php $cont2 = 0
                    @foreach ($grafico[1] as $valor)
                        @php $cont2++;
                        @if ($cont2 === 1)
                            @php echo '['.$valor[0].','.$valor[1].']'
                        @else
                            @php echo ',['.$valor[0].','.$valor[1].']'
                        @endif
                        
                    @endforeach
                @php echo ']'
            @php echo '}'
        @endforeach 
    ];
    
    return histcatexplong
    }
    
    var colors = d3.scale.category20();
    
    /*
    var chart;
    nv.addGraph(function() {
        chart = nv.models.stackedAreaChart()
            .useInteractiveGuideline(true)
            .x(function(d) { return d[0] })
            .y(function(d) { return d[1] })
            .controlLabels({stacked: "Stacked"})
            .duration(300);

        chart.xAxis.tickFormat(function(d) { return d3.time.format('%x')(new Date(d)) });
        chart.yAxis.tickFormat(d3.format(',.4f'));

        chart.legend.vers('furious');

        d3.select('#chart1')
            .datum(histcatexplong)
            .transition().duration(1000)
            .call(chart)
            .each('start', function() {
                setTimeout(function() {
                    d3.selectAll('#chart1 *').each(function() {
                        if(this.__transition__)
                            this.__transition__.duration = 1;
                    })
                }, 0)
            });

        nv.utils.windowResize(chart.update);
        return chart;
    });
    */
   
  

</script>

