<script>
    import { currentUser, allUserBugs } from "../store";
    import { onMount } from "svelte";
    import MainPageBug from "../lib/bugs_info/MainPageBug.svelte";

    const getAllBugs = async() => {
        if($allUserBugs.length > 0) return $allUserBugs;
        let bugs = [];
        for(const project of $currentUser.projects) {
            try {
                const res = await fetch(`/api/projects/bugs?id=${project.idProject}`);
                if(!res.ok) throw new Error(res);
                const json = await res.json();
                bugs = [...bugs, ...json];
            } catch(e) {
                throw new Error(e);
            }
        }
        bugs.sort((a, b) => (a.submittedAt < b.submittedAt) ? 1 : -1);
        allUserBugs.set(bugs);
        return bugs;
    }

    let allBugs = [];
    let shownBugs = [];
    let bugSelectorValue = 'all';
    let bugSeveritySelectorValue = 'all';

    allUserBugs.subscribe(value => {
        allBugs = value;
    });

    $: {
        shownBugs = (bugSelectorValue === 'all') ? allBugs : allBugs.filter(bugs => bugs.projectId === bugSelectorValue);
        shownBugs = (bugSeveritySelectorValue === 'all') ? shownBugs : shownBugs.filter(bugs => bugs.severity === bugSeveritySelectorValue);
    }

    onMount(() => getAllBugs());
</script>

<svelte:head>
    <title>Bugs | Bug Tracker</title>
</svelte:head>
<main>
    <div class="bugs-page-header">
        <span class="title">Bugs</span>
        <select class="bugs-selector" bind:value={bugSelectorValue} on:input={(e) => e.target.blur()}>
            <option value="all">All bugs</option>
            {#each $currentUser.projects as project}
            <option value={project.idProject}>{project.name}</option>
            {/each}
        </select>
        <select class="bugs-selector" bind:value={bugSeveritySelectorValue} on:input={(e) => e.target.blur()}>
            <option value="all">All bugs</option>
            <option value="cosmetic">Cosmetic</option>
            <option value="convenience">Convenience</option>
            <option value="minor">Minor</option>
            <option value="medium">Medium</option>
            <option value="major">Major</option>
            <option value="fatal">Fatal</option>
        </select>
    </div>
    <div class="bugs-list">
        {#if shownBugs.length > 0}
            {#each shownBugs as bug}
            <MainPageBug bug={bug} project={$currentUser.projects.filter(project => project.idProject === bug.projectId)[0]} />
            {/each}
        {:else}
        <h2 style="margin: 2rem auto;">There are no bugs in this category</h2>
        {/if}
    </div>
</main>

<style>
    .bugs-page-header {
        min-width: 300px;
        width: 100%;
        height: fit-content;
        max-height: 5rem;
        padding: 0.5rem 0.75rem;
        font-size: clamp(1.25rem, 3vw, 2rem);
        border-bottom: 2px solid var(--primary-blue);
        display: flex;
        align-items: flex-end;
        gap: 2rem;
    }
    
    .bugs-list {
        width: 100%;
        margin-top: 3rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
    }

    .bugs-selector {
        width: fit-content;
        margin: 0;
        padding: clamp(1px, 0.3%, 4px);
        border-radius: 5px;
        border: 2px solid #ccc;
        font-size: 1rem;
        outline: none;   
        user-select: none;
        transition: border-color 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .bugs-selector:hover {
        border-color: var(--secondary-orange);
    }

    .bugs-selector:focus {
        border-color: var(--secondary-yellow);
    }

</style>