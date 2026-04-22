import {useState} from "react";

function Dashboard() {
    const [count, setCount] = useState(0);

    return (
        <div>
            <h1>Home Page</h1>
            <p>React 18 + Vite starter template 🚀 (pinned to React 18.2.0)</p>
            <button className={'btn btn-primary'} onClick={() => setCount(count + 1)}>
                Count: {count}
            </button>
        </div>
    );
}

export default Dashboard
