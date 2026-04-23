export function AuthCard({ title, children }: { title: string; children: React.ReactNode }) {
    return (
        <div className="login-page">
            <div className="login-card">
                <h1 className="title">{title}</h1>
                {children}
            </div>
        </div>
    );
}
