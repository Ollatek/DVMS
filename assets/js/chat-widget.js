class ChatWidget extends React.Component {
  constructor(props) {
    super(props);
    this.state = { messages: [], input: "", loading: false };
  }

  sendMessage = async () => {
    const { input, messages } = this.state;
    if (!input.trim()) return;
    
    this.setState({ loading: true, messages: [...messages, { sender: "user", text: input }] });

    try {
      cconst response = await fetch("http://127.0.0.1:5000/chat" {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: input }),
      });
      const data = await response.json();
      this.setState({ messages: [...this.state.messages, { sender: "bot", text: data.response }], loading: false });
    } catch (error) {
      this.setState({ messages: [...messages, { sender: "bot", text: "Error fetching response." }], loading: false });
    }
    
    this.setState({ input: "" });
  };

  render() {
    return (
      <div style={{
        position: "fixed", bottom: "20px", right: "20px", width: "300px", backgroundColor: "white",
        borderRadius: "10px", padding: "10px", boxShadow: "0 4px 8px rgba(0,0,0,0.2)"
      }}>
        <h4>AI Assistant</h4>
        <div style={{ height: "200px", overflowY: "auto", borderBottom: "1px solid #ccc", marginBottom: "10px" }}>
          {this.state.messages.map((msg, i) => (
            <p key={i} style={{ textAlign: msg.sender === "user" ? "right" : "left", margin: "5px 0" }}>
              <b>{msg.sender === "user" ? "You" : "AI"}:</b> {msg.text}
            </p>
          ))}
        </div>
        <input
          type="text"
          value={this.state.input}
          onChange={(e) => this.setState({ input: e.target.value })}
          placeholder="Ask me anything..."
          style={{ width: "80%", padding: "5px" }}
        />
        <button onClick={this.sendMessage} disabled={this.state.loading} style={{ marginLeft: "5px" }}>
          {this.state.loading ? "..." : "Send"}
        </button>
      </div>
    );
  }
}

ReactDOM.render(<ChatWidget />, document.getElementById("chat-container"));
