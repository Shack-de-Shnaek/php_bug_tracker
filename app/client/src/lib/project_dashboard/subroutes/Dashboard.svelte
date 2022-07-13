<script>
    import capitalizeFirst from '../../../services/capitalizeFirst';
    import { userIsProjectMember, viewedProject } from '../../../store';
    import NewPost from "../posts/NewPost.svelte";
    import PostList from "../posts/PostList.svelte";
    import BugGraph from "../dashboard/BugGraph.svelte";

    const ProjectStatusStyleLookup = (status) => {
        switch(status) {
            case 'active development':
                return 'active';
            case 'on hiatus':
                return 'paused';
            case 'finished':
                return 'done';
        }
    }
</script>

{#if $viewedProject.idProject}
<div class="container">
    <div class="project-info-container general-container">
    <div class="project-header">
        <span class="project-name">{$viewedProject.name}</span>
        <span class="project-progress">{$viewedProject.progress}% done!</span>
        <span class="project-status {ProjectStatusStyleLookup($viewedProject.status)}">{capitalizeFirst($viewedProject.status)}</span>
    </div>
    <div class="project-content">
        <div class="project-description">{$viewedProject.description}</div>
        <BugGraph />
    </div>
</div>
</div>
{/if}
{#if $userIsProjectMember == true}
<NewPost />
{/if}
<PostList />

<style>
    .project-info-container {
        min-width: min-content;
        width: 100%;
        max-width: 1200px;
        margin: 0.5rem auto;
        margin-top: 2rem;
        padding: 0.5rem;
        border-radius: 10px;
        box-shadow: 2px 2px 7px 3px #777;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .project-header {
        width: 95%;
        height: fit-content;
        padding: 0.5rem 0.25rem;
        border-bottom: 2px solid var(--primary-blue);
        font-size: clamp(1.5rem, 3.5vw, 2rem);
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .project-content {
        width: 100%;
        padding: 0.5rem;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .project-description {
        flex: 4;
        min-width: 300px;
        max-width: 500px;
        padding: 5px;
        border: 1px solid var(--secondary-white);
        border-radius: 5px;
    }

    .project-name {
        text-align: center;
        flex: 2;
        min-width: fit-content;
    }

    .project-progress {
        text-align: center;
        flex: 2;
        min-width: fit-content;
    }

    .project-status {
        text-align: center;
        flex: 0.75;
        height: fit-content;
        min-width: fit-content;
        max-width: fit-content;
        padding: 5px;
        border-radius: 5px;
        font-size: 1.1rem;
        font-weight: 500;
    }

    .active {
        background-color: var(--primary-blue);
    }

    .paused {
        background-color: var(--secondary-yellow);
    }

    .done {
        background-color: var(--secondary-green);
    }

    @media only screen and (max-width: 600px) {
        /* .project-info-container {
            width: 90vw;
        } */
    }
</style>