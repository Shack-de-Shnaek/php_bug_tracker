<script>
    import { router } from 'tinro';
    import { fly } from 'svelte/transition';
    import { currentUser } from '../../store';
    import parseCookies from '../../services/parseCookies';
    import NewProjectModal from '../project/NewProjectModal.svelte';
    import homeSVG from '../icons/home_icon.svg';
    import projectSVG from '../icons/project_icon.svg';
    import postSVG from '../icons/post_icon.svg';
    import bugSVG from '../icons/bug_icon.svg';
    import userSVG from '../icons/user_icon.svg';

    const cookies = parseCookies();

    let projectArray = [];

    let newProjectModal;

    const showNewProjectModal = () => {
        newProjectModal.show();
        projectList.close();
    }

    const getProjects = async () => {
        try {
            const res = await fetch(`/api/users/projects?sessid=${cookies.PHPSESSID}`);
            const json = await res.json();

            if (res.ok) return json;
            else throw new Error(json);
        } catch(e) {
            console.log(e);
            throw new Error(e);
        }
    }
    
    const handleRedirect = (route) => {
        router.goto(route);
    }

    const handleProjectRedirect = (route) => {
        router.goto(route);
    }

    const handleClick = (event, route) => {
        event.target.blur();
        handleRedirect(route);
    }

    let projectList = {
        value: false,
        expand: () => {
            projectList.value = true;
            if($currentUser.projects) projectArray = $currentUser.projects;
            else projectArray = getProjects();
        },
        close: () => projectList.value = false,
        toggle: () => {
            projectList.value = !projectList.value;
            if(projectList.value) {
                if($currentUser.projects) projectArray = $currentUser.projects;
                else projectArray = getProjects();
            }
        }
    }

</script>

<NewProjectModal bind:this={newProjectModal} />
<nav class="nav-container">
    <ul class="nav-list">
        <li class="nav-item" on:click={(e) => handleClick(e, '/')}>
            <img src={homeSVG} alt="home icon" class="nav-item-icon">
            <div class="nav-item-label"><span>Home</span></div>
        </li>
        <li class="nav-item" on:click={(e) => projectList.toggle()}>
            <img src={projectSVG} alt="project icon" class="nav-item-icon">
            <div to="/" class="nav-item-label"><span>Projects</span></div>
        </li>
        <li class="nav-item" on:click={(e) => handleClick(e, '/posts')}>
            <img src={postSVG} alt="account icon" class="nav-item-icon">
            <div class="nav-item-label"><span>Posts</span></div>
        </li>
        <li class="nav-item" on:click={(e) => handleClick(e, '/bugs')}>
            <img src={bugSVG} alt="account icon" class="nav-item-icon">
            <div class="nav-item-label"><span>Bugs</span></div>
        </li>
        <li class="nav-item" on:click={(e) => handleClick(e, '/myaccount')}>
            <img src={userSVG} alt="account icon" class="nav-item-icon">
            <div class="nav-item-label"><span>My account</span></div>
        </li>
    </ul>
    {#if projectList.value}
    <ul class="project-list" in:fly={{x: -200}} out:fly={{x: -200}}>
        <button class="new-project-button" on:click={showNewProjectModal}>New Project</button>
        {#await projectArray}
            <div class="loading">
                Looking for your projects
            </div>
        {:then array} 
            {#if array.length === 0}
                <div>You have no projects</div>
            {:else}
                {#each array as project}
                    <!-- <li class="project"><a href={`/projects/${project.idProject}`}>{project.name}</a></li> -->
                    <li class="project" on:click={handleProjectRedirect(`/projects/${project.idProject}`)}><span>{project.name}</span></li>
                {/each}
            {/if}
        {:catch error}
            <div class="error">
                Could not find your projects T-T
            </div>
        {/await}
    </ul>
    {/if}
</nav>

<style>
    .nav-container {
        z-index: 50;
        box-sizing: border-box;
        position: fixed;
        top: 1rem;
        left: 1rem;
        height: calc(100vh - 2rem);
        width: fit-content;
        padding: 0;
        border-radius: 10px;
        /* background-color: var(--primary-dark); */
        color: var(--primary-white);
        display: grid;
        grid-template-columns: repeat(auto-fit, 1fr);
        grid-template-rows: 1fr;
        gap: 1rem;
    }

    .nav-list {
        z-index: 50;
        box-sizing: border-box;
        width: fit-content;
        height: 100%;
        margin: 0;
        padding: 0.5rem 0.25rem;
        background-color: var(--primary-dark);
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: start;
        grid-column: 1;
    }

    .nav-item {
        list-style: none;
        height: clamp(1.5rem, 5vh, 4rem);
        width: fit-content;
        max-width: 10rem;
        padding: 0.25rem 10px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: start;
        cursor: pointer;
        transition: 0.2s cubic-bezier(0.075, 0.82, 0.165, 1);
    }

    .nav-item-icon {
        width: auto;
        height: 100%;
        pointer-events: none;
        user-select: none;
        transition: 0.2s cubic-bezier(0.075, 0.82, 0.165, 1);
    }

    .nav-item-label {
        overflow: hidden;
        height: 100%;
        width: 0;
        opacity: 0;
        font-size: 1.25rem;
        line-height: 3rem;
        padding: auto 0;
        color: var(--primary-white);
        user-select: none;
        display: flex;
        align-items: center;
        justify-self: start;
        transition: 0.2s cubic-bezier(0.075, 0.82, 0.165, 1);
    }

    .nav-item-label > span {
        max-height: fit-content;
        color: var(--primary-white);
        padding: 0;
        margin: 0;
        text-decoration: none;
        transition: 0.2s cubic-bezier(0.075, 0.82, 0.165, 1);
    }

    .nav-item:hover, .nav-item:focus-within {
        gap: 0.5rem;
        width: 12.5rem;
        max-width: none;
        background-color: var(--primary-blue);
        transition: 0.2s cubic-bezier(0.075, 0.82, 0.165, 1);
    }

    .nav-item:hover .nav-item-label, .nav-item:focus-within .nav-item-label {
        width: auto;
        opacity: 100;
        transition: 0.2s cubic-bezier(0.075, 0.82, 0.165, 1);
    }
    
    .nav-item:hover .nav-item-icon, .nav-item:focus-within .nav-item-icon {   
        transition: 0.2s cubic-bezier(0.075, 0.82, 0.165, 1);
    }

    .project-list {
        z-index: 40;
        box-sizing: border-box;
        width: 10rem;
        height: 100%;
        margin: 0;
        padding: 1rem 0.5rem;
        color: var(--primary-white);
        background-color: var(--primary-dark);
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: start;
        grid-column: 2;
    }

    .new-project-button {
        width: 100%;
        margin-bottom: 2rem;
    }

    .project {
        width: 100%;
        padding: 5px;
        border-top: 1px solid #ccc;
        background-color: inherit;
        cursor: pointer;
        user-select: none;
        transition: background 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .project:hover {
        background-color: hsl(320, 10%, 25%)
    }

    .project:last-child {
        border-bottom: 1px solid #ccc;
    }

    @media only screen and (max-width: 600px) {
        .nav-container {
            height: 4rem;
            width: 95vw;
            max-width: 100%;
            bottom: 0.5rem;
            left: 2.5vw;
            right: auto;
            top: auto;
        }

        .nav-list {
            width: 100%;
            flex-direction: row;
            justify-content: space-evenly;
            align-items: center;
        }

        .nav-item-icon {
            width: auto;
            min-height: 100%;
            min-height: 2rem;
            pointer-events: none;
            user-select: none;
            transition: 0.2s cubic-bezier(0.075, 0.82, 0.165, 1);
        }

        .nav-item:hover, .nav-item:focus-within {
            width: fit-content;
            gap: 0;
        }
        
        .nav-item:hover .nav-item-label, .nav-item:focus-within .nav-item-label {
            width: 0;
            opacity: 0;
        }

        .project-list {
            height: calc(85vh - 5rem);
            position: fixed;
            top: 1rem;
            left: 1rem;
        }
    }
</style>