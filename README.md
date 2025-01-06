# DAST Benchmark

Dynamic Application Security Testing (DAST) Benchmark is a modular framework designed to evaluate and compare the efficiency of various DAST tools. The goal of this project is to assist organizations and professionals in making informed decisions about selecting DAST solutions for their specific environments. The framework supports testing for detection accuracy, technology compatibility, and scanning efficiency.

## Features
- **Support for Various Vulnerabilities**: Includes tests for SQL Injection, Cross-Site Scripting (XSS), Cross-Site Request Forgery (CSRF), Denial-of-Service (DoS), and more.
- **Technology Testing**: Evaluates scanners' compatibility with TLS versions, encoding formats, and authentication methods.
- **Metrics Collection**: Tracks scan duration, request count, and other performance characteristics.
- **Customizability**: Easily extendable to include additional vulnerabilities and technologies.

## Getting Started

### Prerequisites
- Python 3.x
- Docker and Docker Compose

### Installation
```
pip3 install -r requirements.txt
sudo apt install docker.io
sudo apt-get remove docker-compose
sudo curl -SL https://github.com/docker/compose/releases/download/v2.27.0/docker-compose-linux-x86_64 -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose
```

### Usage
The framework is managed via `manager.py`, offering various commands to control the test environment.

```
python3 manager.py <command>
--start:      Create and start all containers
--stop:       Stop all containers
--remove:     Remove all containers
--restart:    Stop, remove, and start all containers
--count-requests: Print the number of requests made to the primary nginx container
--get-time:   Print time difference between the first and last request made to the server
--reset-stats: Reset the number of requests made and the time between the first and last request
```

The URL of the index page containing all paths will be output to the console.

## How It Works
1. **Setup**: Deploys a test environment using Docker containers simulating real-world web applications with predefined vulnerabilities.
2. **Scanners**: Supports integration with popular DAST tools like Burp Suite, ZAP, Nessus, WebInspect, and AppScan.
3. **Benchmarking**: Runs tests, collects results, and compares tool performance based on predefined metrics.

## Contributions
Contributions to add new features, vulnerabilities, or support for additional tools are welcome. Please submit a pull request or open an issue for discussion.

## License
This project is licensed under the Apache License 2.0. See the `LICENSE` file for details.

---

*This project is part of a thesis focused on evaluating the performance of DAST tools.*
