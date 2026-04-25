export function AuthCard({ children }: { title: string; children: React.ReactNode }) {
    return (
        <div className="login-page">
            <div className="login-card">
                {children}
            </div>
        </div>
    );
}
