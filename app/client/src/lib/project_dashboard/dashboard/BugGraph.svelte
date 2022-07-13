<script>
    import { onMount } from "svelte";
    import { Chart, registerables } from 'chart.js';
    import { viewedProject } from '../../../store';

    // let letOldProjectId = $viewedProject;
    // let currentProjectId;
    // viewedProject.subscribe(value =>{
    //     currentProjectId = value.idProject;
    // });

    let submittedBugs = $viewedProject.bugs.filter(bug => bug.status === 'submitted').length;
    let activeBugs = $viewedProject.bugs.filter(bug => bug.status === 'being fixed').length;
    let fixedBugs = $viewedProject.bugs.filter(bug => bug.status === 'fixed').length;

    $: {
        submittedBugs = $viewedProject.bugs.filter(bug => bug.status === 'submitted').length;
        activeBugs = $viewedProject.bugs.filter(bug => bug.status === 'being fixed').length;
        fixedBugs = $viewedProject.bugs.filter(bug => bug.status === 'fixed').length;
    }

    let canvas;
    
    onMount(() => {
        if((submittedBugs + activeBugs + fixedBugs) > 1) {
            const ctx = canvas.getContext('2d');
            Chart.register(...registerables);
            const chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Submitted', 'Being Fixed', 'Fixed'],
                    datasets: [
                        {
                            label: 'Bugs',
                            data: [
                                submittedBugs,
                                activeBugs,
                                fixedBugs
                            ],
                            backgroundColor: [
                                '#f1d102d3',
                                '#77adbb',
                                '#4AAD52'
                            ],
                            borderColor: '#333',
                            borderWidth: 1,
            
                        }
                    ]
                },
                options: {
                    responsive: true,
                    transitions: false,
                    hover: false, 
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Bugs based on status',
                            color: '#333',
                            fullsize: true
                        }
                    },
                }
            });
        }
    });
</script>

{#if ((submittedBugs + activeBugs + fixedBugs) > 1)}    
<div class="bug-graph-container">
    <canvas class="chart" width="200" height="200" bind:this={canvas}></canvas>
</div>
{/if}

<style>
    .bug-graph-container {
        flex: 2;
        min-width: 250px;
        max-width: 300px;
        height: 300px;
        padding: 0;
        margin: 0;
    }
</style>