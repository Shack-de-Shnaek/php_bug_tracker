<script>
    import { router, meta } from 'tinro';
    import { currentUserId, viewedProject, userIsProjectLeader, userIsProjectMember, modalControl } from "../store";
    import capitalizeFirst from '../services/capitalizeFirst';

    let currentNav = 'dashboard';
    let title;
    viewedProject.subscribe(value => {
        title = `${capitalizeFirst(value.name)} | Bug Tracker`;
    });

    let projectId;

    const getProject = async() => {
        if($viewedProject.idProject && $viewedProject.idProject == projectId) return $viewedProject;
        else {
            try {
                const projectRes = await fetch(`/api/projects?id=${projectId}`);
                const json = await projectRes.json();
                if(json.length === 0) {
                    modalControl.show('error', 'That project does not exist');
                } else {
                    const project = await json[0];
                    const membersRes = await fetch(`/api/projects/members?id=${projectId}`);
                    const members = await membersRes.json();
                    const postsRes = await fetch(`/api/projects/posts?id=${projectId}`);
                    const posts = await postsRes.json();
                    const bugsRes = await fetch(`/api/projects/bugs/?id=${projectId}`);
                    const bugs = await bugsRes.json();

                    project.members = await members;
                    project.posts = await posts;
                    project.bugs = await bugs;

                    if(project.leaderId == $currentUserId) userIsProjectLeader.set(true);
                    else userIsProjectLeader.set(false);

                    userIsProjectMember.set(false);
                    members.forEach(user => {
                        if(user.idUser == $currentUserId) {
                            userIsProjectMember.set(true);
                        }
                    });

                    viewedProject.set(project);

                    return project;
                }
            } catch(e) {
                modalControl.show('error', 'Could not load the project');
                console.log(e);
            }
        }
    }
    
    let route = meta();
    route.subscribe(value => {
        try {
            currentNav = value.url.split('/')[3];
            if(currentNav == '' || currentNav == undefined) currentNav = 'dashboard';

            if(projectId !== value.params.id) {
                projectId = value.params.id;
                getProject();   
            }
        } catch(e) {
            null;
        }
    });

    const handleRedirect = (subroute) => {
        currentNav = subroute;
        switch(subroute) {
            case 'dashboard':
                router.goto(`/projects/${projectId}/`);
                break;
            default:
                router.goto(`/projects/${projectId}/${subroute}`);
        }
    }


</script>

<svelte:head>
    <title>{title}</title>
</svelte:head>
<main>
    {#await getProject()}
    <h1>Loading...</h1>
    {:then project} 
    <div class="project-nav">
        <ul class="project-nav-list">
            <li on:click={() => handleRedirect('dashboard')} class:nav-selected={currentNav === 'dashboard'}>Dashboard</li>
            <li on:click={() => handleRedirect('team')} class:nav-selected={currentNav === 'team'}>Team</li>
            <li on:click={() => handleRedirect('bugs')} class:nav-selected={currentNav === 'bugs'}>Bugs</li>
            {#if $userIsProjectLeader == true}
            <li on:click={() => handleRedirect('settings')} class:nav-selected={currentNav === 'settings'}>Settings</li>
            {/if}
        </ul>
    </div>
    <slot>
    
    </slot>
    {:catch}
        <div class="project-header">
            <span class="error">Could not load the project T-T</span>
        </div>
    {/await}
</main>

<style>
    .project-nav {
        min-width: 300px;
        width: 100%;
        height: fit-content;
        max-height: 5rem;
        padding: 0.5rem 0.75rem;
        font-size: clamp(1.25rem, 3vw, 2rem);
        border-bottom: 2px solid var(--primary-blue);
    }

    .project-nav-list {
        list-style: none;
        min-width: 300px;
        width: 100%;
        max-height: 100%;
        font-size: inherit;
        padding: 0;
        margin: 0 auto;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 5%;
    }

    .project-nav-list > li {
        min-width: fit-content;
        padding: 5px;
        border-radius: 10px;
        cursor: pointer;
        user-select: none;
        transition: 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .project-nav-list > li.nav-selected {
        background-color: var(--primary-blue);
        border-radius: 10px;
    }

    .project-header {
        width: 95%;
        height: fit-content;
        padding: 0.5rem 0.25rem;
        border-bottom: 2px solid var(--primary-blue);
        font-size: 2rem;
        display: flex;
        justify-content: space-evenly;
    }

    @media only screen and (max-width: 600px) {
        .project-nav-list {
            justify-content: space-between;
        }

        .project-nav-list > li {
            width: 33%;
            text-align: center;
        }
    }
</style>