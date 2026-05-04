import styles from "./Footer.module.css";

export default function Footer() {
    return (
        <footer className={styles.footer}>
            <p>© {new Date().getFullYear()}. All rights reserved. By: Ante Filipović</p>
        </footer>
    );
}
