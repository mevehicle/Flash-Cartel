

import { useState, useRef, useEffect } from "react";

export default function Navbar({ username = "Flash", editAccountUrl = "edit_account.php", logoutUrl = "logout.php" }) {
  const [open, setOpen] = useState(false);
  const dropdownRef = useRef(null);

  // Close dropdown when clicking outside
  useEffect(() => {
    function handleClickOutside(e) {
      if (dropdownRef.current && !dropdownRef.current.contains(e.target)) {
        setOpen(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  return (
    <nav style={navStyle}>

      {/* Logo */}
      <div style={logoBoxStyle}>
        <span style={{ color: "#6cff4c", fontSize: 22 }}>⚡</span>
        <div style={{ lineHeight: 1.2 }}>
          <div style={{ fontWeight: "bold", fontSize: 14, color: "white" }}>FLASH</div>
          <div style={{ fontWeight: "bold", fontSize: 14, color: "white" }}>CARTEL</div>
        </div>
      </div>

      {/* Welcome message */}
      <div style={welcomeStyle}>
        Welcome to Flash Cartel, {username}!
      </div>

      {/* Edit Account dropdown */}
      <div ref={dropdownRef} style={{ position: "relative" }}>
        <button
          onClick={() => setOpen(!open)}
          style={accountBtnStyle}
        >
          Edit Account {open ? "▴" : "▾"}
        </button>

        {open && (
          <div style={dropdownStyle}>
            <a href={editAccountUrl} style={dropdownItemStyle}>Edit username</a>
            <a href={`${editAccountUrl}?section=email`} style={dropdownItemStyle}>Edit email</a>
            <a href={`${editAccountUrl}?section=password`} style={dropdownItemStyle}>Change password</a>
            <div style={{ borderTop: "1px solid rgba(255,255,255,0.08)" }} />
            <a href={logoutUrl} style={{ ...dropdownItemStyle, color: "#ff6b6b" }}>
              Logout
            </a>
          </div>
        )}
      </div>

    </nav>
  );
}

const navStyle = {
  display: "flex",
  alignItems: "center",
  padding: "12px 24px",
  borderBottom: "1px solid rgba(255,255,255,0.1)",
  fontFamily: "Verdana, Geneva, Tahoma, sans-serif",
};

const logoBoxStyle = {
  display: "flex",
  alignItems: "center",
  gap: 8,
  background: "#1a2a1a",
  padding: "6px 12px",
  borderRadius: 8,
  flexShrink: 0,
};

const welcomeStyle = {
  flex: 1,
  textAlign: "center",
  color: "white",
  fontSize: 22,
  fontWeight: "bold",
};

const accountBtnStyle = {
  background: "none",
  border: "1px solid rgba(108,255,76,0.5)",
  color: "#9adf72",
  padding: "7px 14px",
  borderRadius: 8,
  cursor: "pointer",
  fontFamily: "Verdana, Geneva, Tahoma, sans-serif",
  fontSize: 13,
  flexShrink: 0,
};

const dropdownStyle = {
  position: "absolute",
  right: 0,
  top: "calc(100% + 6px)",
  background: "#0e1f3a",
  border: "1px solid rgba(108,255,76,0.35)",
  borderRadius: 10,
  minWidth: 170,
  overflow: "hidden",
  zIndex: 100,
  boxShadow: "0 8px 24px rgba(0,0,0,0.5)",
};

const dropdownItemStyle = {
  display: "block",
  padding: "10px 16px",
  color: "white",
  fontSize: 13,
  textDecoration: "none",
  fontFamily: "Verdana, Geneva, Tahoma, sans-serif",
  borderBottom: "1px solid rgba(255,255,255,0.07)",
  cursor: "pointer",
};
