pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                sh '''
                    docker compose build php
                    docker compose run --rm php composer install
                '''
            }
        }
        stage('PHPUnit') {
            steps {
                sh '''
                    docker compose run --rm php vendor/bin/phpunit tests
                '''
            }
        }
    }
    post {
        always {
            sh 'docker compose down --volumes'
        }
    }
}
