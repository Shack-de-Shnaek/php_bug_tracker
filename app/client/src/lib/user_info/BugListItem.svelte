    <script>
    import { router } from 'tinro';
    import { viewedProject, modalControl } from '../../store';

    export let data = {};
    export let type = 'bug';

    let open = false;

    const getProject = async() => {
        if (data.projectId == $viewedProject.idProject) return $viewedProject;
        try {
            const res = await fetch(`/api/projects?id=${data.projectId}`);
            if(res.ok) {
                const json = await res.json();
                return json[0];
            } else {
                throw new Error();
            }
        } catch(e) {
            throw new Error(e);
        }
    }
</script>

<li class="info-list-item" class:open={open}>
    <div class="label" on:click={() => open = !open}>
        <div class="title">{(data.name) ? data.name : data.title}</div>
        {#if type === 'post'}
            <div>{data.createdAt.split(' ')[0]}</div>
        {/if}
    </div>
    <div class="info">
        {#await getProject()}
        ...
        {:then project} 
        <button class="project-link" on:click={() => router.goto(`/projects/${project.idProject}`)}>From project: {project.name}</button>
        {/await}
        <div>{(data.description) ? data.description : data.contents}</div>
    </div>
    <!-- {#if open}
    {/if} -->
</li>

<style>
    .info-list-item {
        max-width: 100%;
        height: fit-content;
        font-size: 1.05rem;
        margin: 0.25rem 0;
        padding: 5px;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0;
        overflow: hidden;
        transition: 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .info-list-item:nth-child(even) {
        background-color: #eee;
    }

    .info-list-item::marker {
        display: inline;
    }

    .info-list-item .label {
        height: 100%;
        width: 100%;
        margin: 0;
        font-size: 1.25rem;
        user-select: none;
        cursor: pointer;
    }

    .info-list-item .label .title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .info-list-item .info {
        max-height: 0;
        width: 100%;
        margin: 0;
        padding: 0 5px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        background-color: var(--primary-white);
        font-size: 1rem;
        overflow: hidden;
        transition: max-height 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .info-list-item.open {
        background-color: var(--secondary-yellow);
        gap: 0.5rem;
    }

    .info-list-item.open .label {
        display: flex;
        justify-content: space-between;
    }

    .info-list-item.open .info {
        max-height: 10rem;
        padding: 5px;
        display: flex;
        flex-direction: column;
        align-items: start;
        justify-content: space-between;
    }

    .info-list-item.open .label .title {
        max-width: 100%;
        white-space: unset;
        overflow: unset;
        text-overflow: unset;
    }

    .project-link {
        padding: 0.25rem;
    }
</style>