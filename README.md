Thought about Wazuh multi-server setup for 7 seconds
Yes, this kind of hierarchical or “hub-and-spoke” deployment is definitely possible with Wazuh. In fact, Wazuh offers features that can help you set up a central (or “main”) manager at your organization (the national CSIRT), while remote/branch managers (or even just agents) collect logs and send them up to your central infrastructure.

Below is an overview of how you can structure this:

1. Basic Concepts in Wazuh Architecture
Wazuh Manager

The “brain” of the deployment. It receives and processes log data, applies correlation rules, and generates alerts.
Hosts the RESTful API, which can be queried by the Wazuh Dashboard or other tools.
Wazuh Agent

Installed on endpoints or servers that need to be monitored.
Collects logs, runs FIM, rootcheck, syscheck, etc., and sends data to its Wazuh Manager.
Distributed / Aggregator Manager (optional in large or multi-tenant setups)

In some environments, you can place an additional Wazuh Manager (or managers) in remote sites or small organizations. These aggregator managers handle local traffic from agents and then forward events to the main/central manager or to a central indexer.
Wazuh Indexer (Elasticsearch-based) and Dashboard (Kibana-based)

The indexer stores alerts and events.
The dashboard provides a UI to visualize data and manage alerts.
2. Multi-Tenant or Hierarchical Deployments
You mentioned a scenario where smaller organizations (with limited cybersecurity resources) each have a “sub-server.” Typically, there are two ways to achieve this with Wazuh:

Option A: A single central Wazuh Manager with local agents only

Each small company installs a Wazuh agent on their servers/endpoints.
All those agents connect directly to your central Wazuh Manager (at the national CSIRT).
Pros: Simplest architecture.
Cons: All endpoints are directly sending logs to your central manager. Network latency or bandwidth constraints could be a concern. Also, data segmentation must be carefully managed (role-based access control, etc.) if those organizations ever need direct access to the console.
Option B: Local (remote) Wazuh Manager(s) forwarding events to a central “Master” Manager

Each small company runs its own local Wazuh Manager. That manager receives logs from local agents (so they have real-time local visibility).
Each local Wazuh Manager then forwards alerts (and possibly logs) to the central manager or central indexer at the CSIRT for consolidated visibility.
Pros:
Better control for local security staff if they exist.
Less bandwidth usage if logs are processed locally and only alerts (or summarized events) are forwarded up.
Simpler data segmentation: each sub-organization has its own data domain.
Cons: Slightly more complex to deploy (multiple manager instances, versions to maintain, etc.).
In your case, Option B is more in line with giving each small company a “sub-system” that still reports upstream to you.

3. Mechanisms to Forward Alerts and Logs
3.1 Wazuh Manager-to-Manager Forwarding
Wazuh can be configured so that a remote manager’s alerts (JSON-formatted) are forwarded in near-real-time to a main manager or indexer. This is described in Wazuh’s documentation as “Forwarding events from one manager to another.” Key points:

You can configure the remote manager(s) to send specific data (e.g., only alerts, or alerts + security events) to the central manager via HTTPS.
The central manager/indexer receives these events and stores them in the main cluster, where you can visualize them in the central Wazuh Dashboard.
3.2 Role-Based Access Control (RBAC)
If you want each small company to have a limited or “tenant-like” view of only their own logs/alerts in your central Dashboard, you can use Wazuh’s built-in RBAC:

Create different roles and assign them permissions so that each organization can see only the agents and alerts relevant to them.
This prevents cross-visibility among tenants in the same interface.
Note: True multitenancy (completely separate data indexes for each tenant) is sometimes done by spinning up separate Wazuh Indexer “instances” or using index-per-tenant strategies, but for a small/medium deployment, RBAC on a shared cluster may be enough.

3.3 Distributed / Cluster Mode
For high availability (HA), you can cluster the central Wazuh Manager and Indexer.
Each remote manager can also be set up in HA if needed, but that’s often overkill for a small site.
4. Implementation Steps (High-Level)
Set Up the Central Manager (CSIRT HQ)

Install Wazuh Manager + Wazuh Indexer + Wazuh Dashboard.
Ensure you have enough resources (CPU, RAM, disk) to handle logs from all remote sites.
Secure the connection (TLS) for remote managers/agents to send logs.
Deploy a Local Wazuh Manager (or Wazuh Agent if truly minimal) at Each Organization

If the small company wants local control and local data, you install a local manager. Agents at that site point to the local manager.
Otherwise, if very lightweight, simply install Wazuh Agents on each endpoint pointing to the central manager directly.
Configure Event Forwarding

In the local manager’s /var/ossec/etc/ossec.conf, enable forwarding of alerts (or raw logs) to the central manager.
Make sure you have the proper firewall and networking configurations so that the local manager can reach the central manager securely over HTTPS (port 55000 by default, if using the Wazuh API).
Set Up RBAC / Multi-Tenant Access

In your central Wazuh Dashboard, define user roles and groups for each organization.
Assign the organization’s Wazuh agents or node groups to the relevant roles.
This will limit each company’s view to their own logs/alerts, if they have access to your console at all.
Test the Alert Flow and Visibility

Generate test alerts at the remote site. Confirm that the central manager receives them.
Check that each organization can only see their own data in the dashboard.
5. Considerations
Network Bandwidth

Depending on how verbose your logs are and how many endpoints each small company has, be mindful of bandwidth usage. A local manager + forwarding only critical alerts can reduce overhead.
Privacy / Data Separation

Be sure that your architecture respects privacy laws or data-handling agreements.
In some cases, you might want to store full logs locally and only forward summarized alerts (to reduce sending sensitive data across networks).
Scaling & Maintenance

With each local manager, you need to keep versions updated and ensure consistent rule sets.
Central management of local managers can be done via configuration management tools (Ansible, Puppet, etc.).
Licensing / Open-Source

Wazuh is open-source, so there’s no direct licensing cost for multiple managers or agents. However, plan for the operational overhead of multiple installations.
Conclusion
Yes, it is quite possible to provide each small company with a local “sub-server” (a Wazuh manager) that forwards their logs and alerts to a centrally managed Wazuh infrastructure at your national CSIRT.

This architecture allows each organization to monitor its own systems locally while still giving your central SOC (the CSIRT) full visibility for incident response and threat intelligence.
Properly set up, it also ensures that each tenant’s data can be segmented and controlled via RBAC or multi-index strategies, maintaining confidentiality.
This is a common design for MSSPs (Managed Security Service Providers) and national CERT/CSIRT teams that oversee multiple smaller entities. The official Wazuh documentation (particularly the sections on “Forwarding events from one manager to another” and RBAC) will guide you through setting this up in detail.
