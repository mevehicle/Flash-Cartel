

import { useState } from "react";

// Set this to wherever your PHP handles the form submissions
const LOGIN_ACTION = "index.php";
const REGISTER_ACTION = "register.php";

export default function AuthChoice({ errorMessage = "" }) {
  // "choice" | "login" | "register"
  const [view, setView] = useState("choice");

  return (
    <div
      style={{
        minHeight: "100vh",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        background: "radial-gradient(circle at top, #0b1a3a, #020814)",
        fontFamily: "Verdana, Geneva, Tahoma, sans-serif",
        overflow: "hidden",
      }}
    >
      <div className="slide-in" style={cardStyle}>

        {/* Logo */}
        <div style={{ display: "flex", alignItems: "center", justifyContent: "center", gap: 10, marginBottom: 6 }}>
          <span style={{ color: "#6cff4c", fontSize: 28 }}>⚡</span>
          <span style={{ fontSize: 26, fontWeight: "bold", color: "white", letterSpacing: 1 }}>
            FLASH CARTEL
          </span>
        </div>

        {/* Error message (from PHP redirect e.g. ?error=invalidemail) */}
        {errorMessage && (
          <div style={errorStyle}>{errorMessage}</div>
        )}

        {/* ── View: choice ── */}
        {view === "choice" && (
          <>
            <p style={{ color: "#9adf72", fontSize: 14, margin: "16px 0 20px" }}>
              Welcome! Please choose an option:
            </p>
            <button
              onClick={() => setView("login")}
              style={loginBtnStyle}
            >
              Login
            </button>
            <button
              onClick={() => setView("register")}
              style={signupBtnStyle}
            >
              Sign Up to Flash Cartel
            </button>
          </>
        )}

        {/* ── View: login form ── */}
        {view === "login" && (
          <form method="POST" action={LOGIN_ACTION}>
            <p style={{ color: "#9adf72", fontSize: 13, margin: "14px 0 18px", textAlign: "left" }}>
              <span
                style={{ cursor: "pointer", textDecoration: "underline" }}
                onClick={() => setView("choice")}
              >
                ← Back
              </span>
              &nbsp;&nbsp;Login
            </p>
            <label style={labelStyle}>Username:</label>
            <input type="text" name="username" placeholder="Username..." style={inputStyle} required />

            <label style={labelStyle}>Password:</label>
            <input type="password" name="password" placeholder="Password..." style={inputStyle} required />

            <input type="submit" value="Submit" />

            <a href="forgot_password.php" style={{ display: "block", marginTop: 14, fontSize: 12, color: "#63fb58", textDecoration: "none" }}>
              I forgot my password
            </a>
          </form>
        )}

        {/* ── View: register form ── */}
        {view === "register" && (
          <form method="POST" action={REGISTER_ACTION}>
            <p style={{ color: "#9adf72", fontSize: 13, margin: "14px 0 18px", textAlign: "left" }}>
              <span
                style={{ cursor: "pointer", textDecoration: "underline" }}
                onClick={() => setView("choice")}
              >
                ← Back
              </span>
              &nbsp;&nbsp;Sign Up
            </p>
            <label style={labelStyle}>Please choose a username:</label>
            <input type="text" name="username" placeholder="Username..." style={inputStyle} required />

            <label style={labelStyle}>Please enter your email address:</label>
            <input type="email" name="email" placeholder="Email..." style={inputStyle} required />

            <label style={labelStyle}>Please choose a unique password:</label>
            <input type="password" name="password" placeholder="Password..." style={inputStyle} required />

            <label style={labelStyle}>Please re-enter your password:</label>
            <input type="password" name="password_confirm" placeholder="Password..." style={inputStyle} required />

            <input type="submit" value="Submit" />
          </form>
        )}

      </div>
    </div>
  );
}

/* ── Inline styles ── */

const cardStyle = {
  width: 420,
  padding: 40,
  borderRadius: 25,
  background: "rgba(255,255,255,0.05)",
  backdropFilter: "blur(15px)",
  border: "1px solid rgba(255,255,255,0.2)",
  boxShadow: "0 0 30px rgba(0,0,0,0.6)",
  textAlign: "center",
  color: "white",
};

const errorStyle = {
  background: "rgba(255,100,100,0.12)",
  border: "1px solid rgba(255,100,100,0.35)",
  color: "#ff7b7b",
  padding: "10px 14px",
  borderRadius: 10,
  fontSize: 13,
  margin: "14px 0",
};

const loginBtnStyle = {
  display: "block",
  width: "100%",
  padding: 14,
  fontSize: 16,
  borderRadius: 10,
  border: "2px solid #5cff3b",
  background: "rgba(108,255,76,0.12)",
  color: "#6cff4c",
  cursor: "pointer",
  marginBottom: 12,
  fontFamily: "Verdana, Geneva, Tahoma, sans-serif",
  fontWeight: "bold",
  transition: "0.3s",
};

const signupBtnStyle = {
  display: "block",
  width: "100%",
  padding: 14,
  fontSize: 16,
  borderRadius: 10,
  border: "none",
  background: "linear-gradient(90deg,#6cff4c,#3aa52a)",
  color: "white",
  cursor: "pointer",
  fontFamily: "Verdana, Geneva, Tahoma, sans-serif",
  fontWeight: "bold",
  boxShadow: "0 0 15px rgba(108,255,76,0.7)",
};

const labelStyle = {
  display: "block",
  textAlign: "left",
  marginTop: 16,
  marginBottom: 6,
  fontSize: 13,
  color: "#cfd6e6",
};

const inputStyle = {
  width: "100%",
  padding: 14,
  borderRadius: 10,
  border: "2px solid #5cff3b",
  background: "rgba(0,0,0,0.3)",
  color: "white",
  fontSize: 14,
  outline: "none",
  boxSizing: "border-box",
};
