import os

# --- CONFIGURATION ---
output_file = "full_project_context.txt"

# Folders to ignore completely
ignore_dirs = {
    ".git",
    "node_modules",
    "vendor",
    "__pycache__",
    ".vscode",
    "dist",
    "build",
}

# File extensions to include (add more if needed)
valid_extensions = {
    ".php",
    ".html",
    ".css",
    ".js",
    ".json",
    ".sql",
    ".py",
    ".ts",
    ".jsx",
    ".tsx",
    ".vue",
    ".md",
}

# Specific files to ignore
ignore_files = {"package-lock.json", "composer.lock", output_file, "prepare_for_ai.py"}


def is_text_file(filename):
    return any(filename.endswith(ext) for ext in valid_extensions)


def merge_files():
    with open(output_file, "w", encoding="utf-8") as outfile:
        # Walk the current directory
        for root, dirs, files in os.walk("."):
            # Modify dirs in-place to skip ignored directories
            dirs[:] = [d for d in dirs if d not in ignore_dirs]

            for file in files:
                if file in ignore_files or not is_text_file(file):
                    continue

                file_path = os.path.join(root, file)

                try:
                    with open(file_path, "r", encoding="utf-8") as infile:
                        content = infile.read()

                    # Add a clear header for the AI
                    outfile.write(f"\n{'=' * 50}\n")
                    outfile.write(f"FILE PATH: {file_path}\n")
                    outfile.write(f"{'=' * 50}\n\n")
                    outfile.write(content)
                    outfile.write("\n\n")
                    print(f"Added: {file_path}")

                except Exception as e:
                    print(f"Skipped {file_path} (Error: {e})")


if __name__ == "__main__":
    merge_files()
    print(f"\nDone! Upload '{output_file}' to the chat.")
