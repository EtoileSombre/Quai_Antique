# ============================================
# Quai Antique — Commandes projet
# ============================================

# Démarrer l'application en développement
dev:
	docker compose -f infra/docker-compose.dev.yml up -d --build

# Initialiser la base de données (premier lancement)
dev-init:
	docker compose -f infra/docker-compose.dev.yml up -d --build
	@echo "Base de données initialisée."

# Arrêter l'application
dev-stop:
	docker compose -f infra/docker-compose.dev.yml down

# Voir les logs
dev-logs:
	docker compose -f infra/docker-compose.dev.yml logs -f

# Reconstruire sans cache
dev-rebuild:
	docker compose -f infra/docker-compose.dev.yml up -d --build --force-recreate
