<script>
    import { Route } from 'tinro';
    import { currentUser } from './store';
    import Nav from "./lib/nav/Nav.svelte";
    import Login from "./routes/Login.svelte";
    import Home from "./routes/Home.svelte";
    import User from "./routes/User.svelte";
    import Project from "./routes/Projects.svelte";
    import Posts from "./routes/Posts.svelte";
    import Bugs from "./routes/Bugs.svelte"
    import ProjectDashboard from "./lib/project_dashboard/subroutes/Dashboard.svelte";
    import ProjectTeam from "./lib/project_dashboard/subroutes/Team.svelte";
    import ProjectBugs from "./lib/project_dashboard/subroutes/Bugs.svelte";
    import ProjectSettings from "./lib/project_dashboard/subroutes/Settings.svelte";
</script>

<Route path="myaccount">
    <Nav />
    <User userId={$currentUser.idUser} />
</Route>
<Route path="/bugs/*">
    <Nav />
    <Route path="/">
        <Bugs />
    </Route>
</Route>
<Route path="/posts/*">
    <Nav />
    <Route path="/">
        <Posts />
    </Route>
</Route>
<Route path="/users/*">
    <Nav />
    <Route path="/">
        <h1>fuck you</h1>
    </Route>
    <Route path="/:id" let:meta>
        <User userId={meta.params.id} />
    </Route>
</Route>
<Route path="/projects/*">
    <Nav />
    <Route path="/">
        <h1>fuck you</h1>
    </Route>
    <Route path="/:id/*" let:meta>
        <Project>
            <Route path="/">
                <ProjectDashboard />
            </Route>
            <Route path="/team">
                <ProjectTeam />
            </Route>
            <Route path="/bugs">
                <ProjectBugs />
            </Route>
            <Route path="/settings">
                <ProjectSettings />
            </Route>
        </Project>
    </Route>
</Route>
<Route path="/">
    <Nav />
    <Home />
</Route>
<Route path="/login">
    <Login />
</Route>