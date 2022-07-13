<script>
    import { viewedProject } from "../../../store";
    import Bug from "./Bug.svelte";

    let list = [];
    viewedProject.subscribe(value => {
        list = value.bugs;
    });
</script>

<div class="bug-list">
    {#each list as bug}
    <Bug bug={bug} />
    {/each}
</div>

<style>
    .bug-list {
        width: 100%;
        margin-top: 3rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
    }
</style>