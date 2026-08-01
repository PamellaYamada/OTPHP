package main

import (
	"bufio"
	"bytes"
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"os"
	"os/exec"
	"path/filepath"
	"strings"
)

type Config struct {
	GithubUser   string `json:"github_user"`
	GithubToken  string `json:"github_token"`
	GeminiAPIKey string `json:"gemini_api_key"`
}

type GeminiPayload struct {
	Contents []struct {
		Parts []struct {
			Text string `json:"text"`
		} `json:"parts"`
	} `json:"contents"`
}

type GeminiResponse struct {
	Candidates []struct {
		Content struct {
			Parts []struct {
				Text string `json:"text"`
			} `json:"parts"`
		} `json:"content"`
	} `json:"candidates"`
}

type GithubRepoPayload struct {
	Name        string `json:"name"`
	Description string `json:"description"`
	Private     bool   `json:"private"`
}

type GithubRepoResponse struct {
	CloneURL string `json:"clone_url"`
	Message  string `json:"message"`
}

func getConfigPath() string {
	home, _ := os.UserHomeDir()
	dir := filepath.Join(home, ".config", "gitx")
	os.MkdirAll(dir, 0700)
	return filepath.Join(dir, "config.json")
}

func loadConfig() (*Config, error) {
	path := getConfigPath()
	data, err := os.ReadFile(path)
	if err != nil {
		return nil, err
	}
	var cfg Config
	err = json.Unmarshal(data, &cfg)
	return &cfg, err
}

func saveConfig(cfg *Config) error {
	path := getConfigPath()
	data, _ := json.MarshalIndent(cfg, "", "  ")
	return os.WriteFile(path, data, 0600)
}

func readLine(reader *bufio.Reader, prompt string) string {
	fmt.Print(prompt)
	text, _ := reader.ReadString('\n')
	return strings.TrimSpace(text)
}

func execCmd(name string, args ...string) (string, error) {
	cmd := exec.Command(name, args...)
	out, err := cmd.CombinedOutput()
	return strings.TrimSpace(string(out)), err
}

func setupConfig(reader *bufio.Reader) *Config {
	fmt.Println("\n=== CONFIGURAÇÃO INICIAL (GITX) ===")
	cfg := &Config{}

	cfg.GithubUser = readLine(reader, "👤 Usuário GitHub: ")
	cfg.GithubToken = readLine(reader, "🔑 Token GitHub (PAT): ")
	cfg.GeminiAPIKey = readLine(reader, "🤖 Chave API Gemini: ")

	if err := saveConfig(cfg); err != nil {
		fmt.Printf("❌ Erro ao salvar config: %v\n", err)
	} else {
		fmt.Println("✅ Configurações salvas em ~/.config/gitx/config.json")
	}

	execCmd("git", "config", "--global", "credential.helper", "store")
	return cfg
}

func generateAICommit(cfg *Config, diff string) string {
	if cfg.GeminiAPIKey == "" || diff == "" {
		return "feat: atualizações no projeto"
	}

	fmt.Println("🤖 Solicitando mensagem de commit à IA...")

	prompt := "Gere uma mensagem de commit em português no padrão Conventional Commits (ex: feat: algo, fix: algo). Responda APENAS a mensagem, sem aspas nem explicações. Mudanças:\n" + diff

	payload := GeminiPayload{}
	payload.Contents = append(payload.Contents, struct {
		Parts []struct {
			Text string `json:"text"`
		} `json:"parts"`
	}{
		Parts: []struct {
			Text string `json:"text"`
		}{{Text: prompt}},
	})

	jsonData, _ := json.Marshal(payload)
	url := "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" + cfg.GeminiAPIKey

	resp, err := http.Post(url, "application/json", bytes.NewBuffer(jsonData))
	if err != nil {
		fmt.Printf("⚠️ Erro ao conectar na API da IA: %v\n", err)
		return "feat: atualizações no código"
	}
	defer resp.Body.Close()

	body, _ := io.ReadAll(resp.Body)
	var gResp GeminiResponse
	json.Unmarshal(body, &gResp)

	if len(gResp.Candidates) > 0 && len(gResp.Candidates[0].Content.Parts) > 0 {
		return strings.TrimSpace(gResp.Candidates[0].Content.Parts[0].Text)
	}

	return "feat: atualizações gerais"
}

func autoCommitPush(cfg *Config, reader *bufio.Reader) {
	fmt.Println("\n--- 🚀 AUTO COMMIT & PUSH ---")

	status, err := execCmd("git", "status", "--porcelain")
	if err != nil {
		fmt.Println("❌ Erro: Esta pasta não é um repositório Git. Rode 'git init' primeiro.")
		return
	}
	if status == "" {
		fmt.Println("✔ Nenhuma alteração pendente.")
		return
	}

	fmt.Println("📄 Alterações encontradas:")
	fmt.Println(status)

	execCmd("git", "add", ".")
	fmt.Println("✅ Arquivos adicionados (git add .)")

	diff, _ := execCmd("git", "diff", "--cached")
	if len(diff) > 2000 {
		diff = diff[:2000]
	}

	aiMsg := generateAICommit(cfg, diff)
	fmt.Printf("\n💡 Sugestão da IA: [%s]\n", aiMsg)

	userInput := readLine(reader, "Pressione ENTER para aceitar ou digite sua mensagem: ")
	finalMsg := aiMsg
	if userInput != "" {
		finalMsg = userInput
	}

	_, err = execCmd("git", "commit", "-m", finalMsg)
	if err != nil {
		fmt.Printf("❌ Erro no commit: %v\n", err)
		return
	}

	branch, _ := execCmd("git", "rev-parse", "--abbrev-ref", "HEAD")
	fmt.Printf("📤 Fazendo push para origin/%s...\n", branch)

	outPush, errPush := execCmd("git", "push", "origin", branch)
	if errPush != nil {
		fmt.Printf("❌ Erro no push:\n%s\n", outPush)
	} else {
		fmt.Println("🎉 Sincronizado com sucesso!")
	}
}

func createGithubRepo(cfg *Config, reader *bufio.Reader) {
	fmt.Println("\n--- 📦 CRIAR REPOSITÓRIO NO GITHUB ---")

	repoName := readLine(reader, "Nome do Repositório: ")
	repoDesc := readLine(reader, "Descrição: ")
	isPrivateStr := readLine(reader, "Privado? (s/N): ")

	isPrivate := strings.ToLower(isPrivateStr) == "s"

	// Inicializa local se necessário
	_, err := execCmd("git", "rev-parse", "--is-inside-work-tree")
	if err != nil {
		fmt.Println("⚙️ Inicializando git localmente...")
		execCmd("git", "init")
		execCmd("git", "branch", "-M", "main")
		os.WriteFile("README.md", []byte("# "+repoName), 0644)
		execCmd("git", "add", "README.md")
		execCmd("git", "commit", "-m", "docs: initial commit")
	}

	fmt.Println("🌐 Criando repositório na API do GitHub...")

	payload := GithubRepoPayload{
		Name:        repoName,
		Description: repoDesc,
		Private:     isPrivate,
	}
	jsonData, _ := json.Marshal(payload)

	req, _ := http.NewRequest("POST", "https://api.github.com/user/repos", bytes.NewBuffer(jsonData))
	req.SetBasicAuth(cfg.GithubUser, cfg.GithubToken)
	req.Header.Set("Content-Type", "application/json")

	client := &http.Client{}
	resp, err := client.Do(req)
	if err != nil {
		fmt.Printf("❌ Erro na requisição HTTP: %v\n", err)
		return
	}
	defer resp.Body.Close()

	body, _ := io.ReadAll(resp.Body)
	var ghResp GithubRepoResponse
	json.Unmarshal(body, &ghResp)

	if ghResp.CloneURL != "" {
		fmt.Printf("✅ Repositório criado: %s\n", ghResp.CloneURL)
		execCmd("git", "remote", "add", "origin", ghResp.CloneURL)
		fmt.Println("📤 Enviando arquivos iniciais...")
		outPush, errPush := execCmd("git", "push", "-u", "origin", "HEAD")
		if errPush != nil {
			fmt.Printf("⚠️ Erro no push inicial: %s\n", outPush)
		} else {
			fmt.Println("🎉 Repositório criado e publicado com sucesso!")
		}
	} else {
		fmt.Printf("❌ Falha ao criar repositório. Resposta do GitHub:\n%s\n", string(body))
	}
}

func main() {
	reader := bufio.NewReader(os.Stdin)

	cfg, err := loadConfig()
	if err != nil || cfg.GithubUser == "" {
		cfg = setupConfig(reader)
	}

	if len(os.Args) > 1 {
		switch os.Args[1] {
		case "commit", "push":
			autoCommitPush(cfg, reader)
			return
		case "create":
			createGithubRepo(cfg, reader)
			return
		}
	}

	fmt.Println("\n=== GITX TERMINAL CLIENT ===")
	fmt.Println("1) 🚀 Auto Commit (IA) & Push")
	fmt.Println("2) 📦 Criar Novo Repositório no GitHub")
	fmt.Println("3) ⚙️ Resetar / Configurar Credenciais")
	fmt.Println("0) ❌ Sair")

	opt := readLine(reader, "\nEscolha uma opção: ")

	switch opt {
	case "1":
		autoCommitPush(cfg, reader)
	case "2":
		createGithubRepo(cfg, reader)
	case "3":
		setupConfig(reader)
	case "0":
		os.Exit(0)
	default:
		fmt.Println("Opção inválida.")
	}
}

