<script>
    import { router } from 'tinro';
    import { viewedUser, currentUser } from '../store';
    import parseCookies from '../services/parseCookies';
    import userSVG from '../lib/icons/user_icon.svg';
    import emailSVG from '../lib/icons/email_icon.svg';
    import projectSVG from '../lib/icons/project_icon.svg';
    import postSVG from '../lib/icons/post_icon.svg';
    import bugSVG from '../lib/icons/bug_icon.svg';
    import capitalizeFirst from '../services/capitalizeFirst';
    import UserInfo from '../lib/user_info/UserInfo.svelte';
    import UserBugs from '../lib/user_info/UserBugs.svelte';
    import UpdateUser from '../lib/user_info/UpdateUser.svelte';

    export let userId;
    let title;
    viewedUser.subscribe(value => {
        title = `${capitalizeFirst(value.name)} ${capitalizeFirst(value.surname)} | Bug Tracker`;
    });

    const getUser = async (id) => {
        if($currentUser.idUser != undefined && $currentUser.idUser === id) {
            viewedUser.set($currentUser);
            return $viewedUser;
        }
        else if($viewedUser.idUser != undefined && $viewedUser.idUser === id) {
            return $viewedUser;
        }
        else {
            try {
                const res = await fetch(`/api/users?id=${id}`);
                const json = await res.json();

                if(res.ok) {
                    if(json.length === 0) {
                        throw new Error(final);
                    }
                    const user = await json[0];

                    const projectsRes = await fetch(`/api/users/projects?id=${id}`);
                    const projects = await projectsRes.json();
                    const postsRes = await fetch(`/api/users/posts?id=${id}`);
                    const posts = await postsRes.json();
                    const submittedBugsRes = await fetch(`/api/users/bugs/submitted?id=${id}`);
                    const submittedBugs = await submittedBugsRes.json();
                    const fixedBugsRes = await fetch(`/api/users/bugs/fixed?id=${id}`);
                    const fixedBugs = await fixedBugsRes.json();

                    user.projects = projects;
                    user.posts = posts;
                    user.bugs = await {
                        submitted: submittedBugs,
                        fixed: fixedBugs
                    };

                    viewedUser.set(user);
                    return user;
                } else {
                    throw new Error(user);
                }
            } catch(e) {
                throw new Error(e);
            }
        }
    }

    const handleLogOut = async() => {
        try {
            const data = new FormData();
            data.append('email', $currentUser.email);
            data.append('sessid', parseCookies().PHPSESSID);
            const res = await fetch('/api/logout', {
                method: 'POST',
                body: data
            });
            if(res.ok) {
                document.cookie = `PHPSESSID=0;expires=Thu, 01 Jan 1970 00:00:00 GMT`;
                currentUser.update(value => {
                    return {
                        idUser: 0,
                        name: '',
                        surname: '',
                        email: '',
                        password: '',
                        projects: [],
                        posts: [],
                        bugs: []
                    }
                });
                router.goto('/login');
            }
        } catch(e) {
            throw new Error(e);
        }
    } 
</script>


<svelte:head>
    <title>{title}</title>
</svelte:head>
<main>
    <div class="row">
        <div class="user-info-container general-container">
            {#await getUser(userId)}
            <div class="img-name-wrapper">
                <div class="img-wrapper">
                    <img src={userSVG} alt="user icon">
                </div>
                <span>Loading...</span>
            </div>
            {:then user} 
            <div class="img-name-wrapper">
                <div class="img-wrapper">
                    <img src={userSVG} alt="user icon">
                </div>
                <span>{capitalizeFirst(user.name)} {capitalizeFirst(user.surname)}</span>
            </div>
            <div class="email-wrapper">
                <div class="email-img-wrapper">
                    <img src={emailSVG} alt="user icon">
                </div>
                <a href={`mailto: ${user.email}`}>{user.email}</a>
            </div>
                {#if user.idUser == $currentUser.idUser}
                <button on:click={() => handleLogOut()} style="margin-left: 0.5rem;">Log out</button>
                <UpdateUser />
                {/if}
            {:catch}
            <div class="img-name-wrapper">
                <div class="img-wrapper">
                    <img src={userSVG} alt="user icon">
                </div>
                <span class="error">Could not find the user</span>
            </div>
            {/await}
        </div>
        <UserInfo type="project" icon={projectSVG} />
    </div>
    <div class="row post-bug-row">
        <UserInfo type="post" icon={postSVG} />
        <UserBugs icon={bugSVG} />
    </div>
</main>

<style>
    main {
        display: flex;
        align-items: flex-start;
        flex-direction: column;
        justify-content: space-evenly;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .row {
        flex: 1;
        height: fit-content;
        min-width: 350px;
        width: 100%;
        padding: 0.5rem 0;
        margin: 0;
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        justify-content: space-around;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .row > * {
        min-width: 350px;
    }

    .post-bug-row {
        align-items: flex-start;
    }

    .user-info-container {
        height: fit-content;
        max-height: 500px;
    }

    .img-name-wrapper {
        height: fit-content;
        /* max-height: 7.5rem; */
        padding: 0.5rem;
        display: flex;
        flex-direction: row;
        gap: 0.5rem;
        align-items: center;
        justify-content: start;
    }
    
    .img-wrapper {
        /* height: 5rem; */
        min-width: 3rem;
        width: 20%;
        max-width: 5rem;
        border-radius: 25%;
        padding: 0.5rem;
        background-color: var(--primary-blue);
        display: grid;
        place-items: center;
    }

    .img-wrapper > img {
        width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .img-name-wrapper > span {
        min-width: fit-content;
        width: 80%;
        height: 5rem;
        font-size: 1.75rem;
        line-height: 4.75rem;
        padding-bottom: 0.25rem;
        border-bottom: 3px solid var(--primary-blue);
    }

    .email-wrapper {
        height: 4rem;
        width: 100%;
        padding: 0.5rem;
        font-size: 1.1rem;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: start;
        gap: 0.5rem;
    }

    .email-img-wrapper {
        height: 75%;
        width: fit-content;
        background-color: var(--primary-blue);
        padding: 0.4rem;
        border-radius: 25%;
        display: grid;
        place-items: center;
    }

    .email-img-wrapper > img {
        height: 100%;
        margin: 0;
    }

    @media only screen and (max-width: 600px) {
        .user-info-container {
            margin: 0 auto;
        }
    }
</style>